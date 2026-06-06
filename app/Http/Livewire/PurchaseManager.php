<?php

namespace App\Http\Livewire;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Database\Seeders\LegacyPurchaseSeeder;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseManager extends Component
{
    use AuthorizesRequests;

    public array $rows = [];
    public int | null $editingPurchaseId = null;
    public float $total = 0;
    public ?string $statusMessage = null;

    protected $listeners = [
        'edit-purchase' => 'editPurchase',
    ];

    protected function rules(): array
    {
        return [
            'rows.*.item_name' => ['required', 'string', 'max:255'],
            'rows.*.brand_name' => ['required', 'string', 'max:255'],
            'rows.*.qty' => ['required', 'integer', 'min:1'],
            'rows.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function mount(): void
    {
        $this->authorize('viewAny', Purchase::class);
        $this->resetRows();
    }

    public function render(): View
    {
        return view('livewire.purchase-manager', [
            'isAdmin' => Auth::user()?->isAdmin() ?? false,
        ]);
    }

    public function addRow(): void
    {
        $this->rows[] = $this->emptyRow();
    }

    public function removeRow(int $index): void
    {
        if (count($this->rows) <= 1) {
            return;
        }

        array_splice($this->rows, $index, 1);
        $this->recalculateTotal();
        $this->resetValidation('rows');
    }

    public function savePurchase(): void
    {
        $this->authorize('create', Purchase::class);
        $this->recalculateTotal();
        $this->validate();
        $this->checkDuplicateRows();

        if ($this->getErrorBag()->isNotEmpty()) {
            $this->statusMessage = 'Please fix duplicate row errors before saving.';
            return;
        }

        DB::transaction(function () {
            $purchase = Purchase::create(['total' => $this->total]);

            foreach ($this->rows as $row) {
                $item = Item::firstOrCreate(['name' => trim($row['item_name'])]);
                $brand = Brand::firstOrCreate(['name' => trim($row['brand_name'])]);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $item->id,
                    'brand_id' => $brand->id,
                    'qty' => $row['qty'],
                    'price' => $row['price'],
                ]);
            }
        });

        $this->statusMessage = 'Purchase created successfully.';
        $this->resetRows();
        $this->dispatch('purchases-changed');
    }

    public function editPurchase(int $purchaseId): void
    {
        $purchase = Purchase::with(['items.item', 'items.brand'])->findOrFail($purchaseId);
        $this->authorize('update', $purchase);

        $this->editingPurchaseId = $purchase->id;
        $this->rows = $purchase->items->map(fn ($item) => [
            'item_name' => $item->item->name,
            'brand_name' => $item->brand->name,
            'qty' => $item->qty,
            'price' => $item->price,
        ])->toArray();

        $this->recalculateTotal();
        $this->statusMessage = 'Editing purchase #' . $purchase->id;
    }

    public function updatePurchase(): void
    {
        $purchase = Purchase::findOrFail($this->editingPurchaseId);
        $this->authorize('update', $purchase);
        $this->recalculateTotal();
        $this->validate();
        $this->checkDuplicateRows();

        if ($this->getErrorBag()->isNotEmpty()) {
            $this->statusMessage = 'Please fix duplicate row errors before updating.';
            return;
        }

        DB::transaction(function () use ($purchase) {
            $purchase->items()->delete();
            $purchase->update(['total' => $this->total]);

            foreach ($this->rows as $row) {
                $item = Item::firstOrCreate(['name' => trim($row['item_name'])]);
                $brand = Brand::firstOrCreate(['name' => trim($row['brand_name'])]);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $item->id,
                    'brand_id' => $brand->id,
                    'qty' => $row['qty'],
                    'price' => $row['price'],
                ]);
            }
        });

        $this->statusMessage = 'Purchase updated successfully.';
        $this->cancelEdit();
        $this->dispatch('purchases-changed');
    }

    public function importLegacy(): void
    {
        $this->authorize('runMigration', Purchase::class);
        app(LegacyPurchaseSeeder::class)->run();
        $this->statusMessage = 'Legacy data imported successfully.';
        $this->dispatch('purchases-changed');
    }

    public function cancelEdit(): void
    {
        $this->editingPurchaseId = null;
        $this->resetRows();
    }

    protected function resetRows(): void
    {
        $this->rows = [$this->emptyRow()];
        $this->recalculateTotal();
        $this->statusMessage = null;
        $this->resetValidation('rows');
    }

    protected function emptyRow(): array
    {
        return [
            'item_name' => '',
            'brand_name' => '',
            'qty' => 1,
            'price' => 0,
        ];
    }

    protected function recalculateTotal(): void
    {
        $this->total = array_reduce($this->rows, fn ($sum, $row) => $sum + ((float) $row['qty'] * (float) $row['price']), 0);
    }

    protected function checkDuplicateRows(): void
    {
        $pairs = [];

        foreach ($this->rows as $index => $row) {
            $key = strtolower(trim($row['item_name'])) . '|' . strtolower(trim($row['brand_name']));

            if ($key === '|') {
                continue;
            }

            if (isset($pairs[$key])) {
                $this->addError('rows.' . $index . '.item_name', 'Duplicate item and brand combination is not allowed.');
                $this->addError('rows.' . $index . '.brand_name', 'Duplicate item and brand combination is not allowed.');
            }

            $pairs[$key] = true;
        }
    }

    public function updated($name): void
    {
        if (str_starts_with($name, 'rows.')) {
            $this->checkDuplicateRows();
        }
    }
}

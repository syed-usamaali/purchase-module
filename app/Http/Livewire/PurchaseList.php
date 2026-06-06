<?php

namespace App\Http\Livewire;

use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseList extends Component
{
    use AuthorizesRequests;

    protected $listeners = [
        'purchases-changed' => 'refreshList',
    ];

    public function refreshList(): void
    {
    }

    public function deletePurchase(int $purchaseId): void
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $this->authorize('delete', $purchase);
        $purchase->delete();

        $this->dispatch('purchases-changed');
    }

    public function editPurchase(int $purchaseId): void
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $this->authorize('update', $purchase);

        $this->dispatch('edit-purchase', purchaseId: $purchaseId);
    }

    public function render(): View
    {
        return view('livewire.purchase-list', [
            'purchases' => Purchase::query()
                ->with(['items.item', 'items.brand'])
                ->orderByDesc('created_at')
                ->get(),
            'isAdmin' => Auth::user()?->isAdmin() ?? false,
        ]);
    }
}

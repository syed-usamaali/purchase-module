<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Database\Seeders\LegacyPurchaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Purchase::class);

        return view('purchases.index');
    }

    public function show(Purchase $purchase)
    {
        Gate::authorize('view', $purchase);

        return response()->json(
            $purchase->load(['items.item', 'items.brand'])
        );
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Purchase::class);

        $data = $request->validate([
            'item_name' => ['required', 'string'],
            'brand_name' => ['required', 'string'],
            'qty' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

        $item = Item::firstOrCreate(['name' => trim($data['item_name'])]);
        $brand = Brand::firstOrCreate(['name' => trim($data['brand_name'])]);

        $purchase = DB::transaction(function () use ($data, $item, $brand) {
            $purchase = Purchase::create([
                'total' => $data['qty'] * $data['price'],
            ]);

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'item_id' => $item->id,
                'brand_id' => $brand->id,
                'qty' => $data['qty'],
                'price' => $data['price'],
            ]);

            return $purchase;
        });

        return response()->json($purchase->load(['items.item', 'items.brand']), 201);
    }

    public function update(Request $request, Purchase $purchase)
    {
        Gate::authorize('update', $purchase);

        $data = $request->validate([
            'item_name' => ['sometimes', 'string'],
            'brand_name' => ['sometimes', 'string'],
            'qty' => ['sometimes', 'integer', 'min:1'],
            'price' => ['sometimes', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($purchase, $data) {
            if (! empty($data['item_name']) || ! empty($data['brand_name']) || isset($data['qty']) || isset($data['price'])) {
                $item = ! empty($data['item_name'])
                    ? Item::firstOrCreate(['name' => trim($data['item_name'])])
                    : null;

                $brand = ! empty($data['brand_name'])
                    ? Brand::firstOrCreate(['name' => trim($data['brand_name'])])
                    : null;

                $purchaseItem = $purchase->items()->first();

                if ($purchaseItem !== null) {
                    $purchaseItem->update(array_filter([
                        'item_id' => $item?->id,
                        'brand_id' => $brand?->id,
                        'qty' => $data['qty'] ?? null,
                        'price' => $data['price'] ?? null,
                    ], fn ($value) => $value !== null));
                }
            }

            $total = $purchase->items()->sum(DB::raw('qty * price'));
            $purchase->update(['total' => $total]);
        });

        return response()->json($purchase->load(['items.item', 'items.brand']));
    }

    public function destroy(Purchase $purchase)
    {
        Gate::authorize('delete', $purchase);

        $purchase->delete();

        return response()->json(status: 204);
    }

    public function importLegacy()
    {
        Gate::authorize('runMigration', Purchase::class);

        app(LegacyPurchaseSeeder::class)->run();

        return response()->json(['message' => 'Legacy purchase import completed.']);
    }
}

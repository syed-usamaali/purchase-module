<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegacyPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legacyPurchases = [
            [
                'item_name' => 'Sugar',
                'brand_name' => 'ABC',
                'qty' => 10,
                'price' => 100,
            ],
        ];

        foreach ($legacyPurchases as $legacy) {
            $itemName = trim($legacy['item_name']);
            $brandName = trim($legacy['brand_name']);
            $qty = (int) $legacy['qty'];
            $price = (float) $legacy['price'];
            $total = $qty * $price;

            $item = Item::firstOrCreate(['name' => $itemName]);
            $brand = Brand::firstOrCreate(['name' => $brandName]);

            $existingPurchaseItem = PurchaseItem::where('item_id', $item->id)
                ->where('brand_id', $brand->id)
                ->where('qty', $qty)
                ->where('price', $price)
                ->first();

            if ($existingPurchaseItem) {
                continue;
            }

            DB::transaction(function () use ($item, $brand, $qty, $price, $total) {
                $purchase = Purchase::create(['total' => $total]);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_id' => $item->id,
                    'brand_id' => $brand->id,
                    'qty' => $qty,
                    'price' => $price,
                ]);
            });
        }
    }
}

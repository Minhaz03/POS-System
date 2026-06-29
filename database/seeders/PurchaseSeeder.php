<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Unit;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::first();
        $flour = Product::where('sku', 'RAW-FLOUR-001')->first();
        $sugar = Product::where('sku', 'RAW-SUGAR-001')->first();
        $butter = Product::where('sku', 'RAW-BUTTER-001')->first();
        $unitKg = Unit::where('short_name', 'kg')->first();

        if ($supplier && $flour && $sugar && $butter) {
            $purchase = Purchase::create([
                'supplier_id' => $supplier->id,
                'reference_no' => 'PUR-' . date('Ymd') . '-001',
                'purchase_date' => now()->subDays(5),
                'status' => 'received',
                'payment_status' => 'paid',
                'subtotal' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'shipping_cost' => 0,
                'grand_total' => 0,
                'amount_paid' => 0,
                'notes' => 'Initial stock purchase'
            ]);

            $subtotal = 0;

            // Flour 100 kg
            $flourTotal = 100 * $flour->cost_price;
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $flour->id,
                'quantity' => 100,
                'unit_id' => $unitKg->id,
                'unit_cost' => $flour->cost_price,
                'subtotal' => $flourTotal,
            ]);
            $subtotal += $flourTotal;

            // Sugar 50 kg
            $sugarTotal = 50 * $sugar->cost_price;
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $sugar->id,
                'quantity' => 50,
                'unit_id' => $unitKg->id,
                'unit_cost' => $sugar->cost_price,
                'subtotal' => $sugarTotal,
            ]);
            $subtotal += $sugarTotal;

            // Butter 20 kg
            $butterTotal = 20 * $butter->cost_price;
            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $butter->id,
                'quantity' => 20,
                'unit_id' => $unitKg->id,
                'unit_cost' => $butter->cost_price,
                'subtotal' => $butterTotal,
            ]);
            $subtotal += $butterTotal;

            $purchase->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
                'amount_paid' => $subtotal
            ]);
        }
    }
}

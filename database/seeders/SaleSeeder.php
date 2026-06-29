<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();
        $bun = Product::where('sku', 'BAK-BUN-01')->first();
        $cake = Product::where('sku', 'BAK-CAKE-POUND')->first();

        if ($bun && $cake) {
            $sale = Sale::create([
                'invoice_no' => 'INV-' . date('Ymd') . '-001',
                'customer_id' => $customer ? $customer->id : null,
                'sale_date' => now(),
                'status' => 'completed',
                'payment_method' => 'cash',
                'subtotal' => 0,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'grand_total' => 0,
                'amount_tendered' => 0,
            ]);

            $subtotal = 0;

            // Sell 5 Buns
            $bunTotal = 5 * $bun->sale_price;
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $bun->id,
                'quantity' => 5,
                'unit_price' => $bun->sale_price,
                'subtotal' => $bunTotal,
            ]);
            $subtotal += $bunTotal;

            // Sell 2 Cakes
            $cakeTotal = 2 * $cake->sale_price;
            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $cake->id,
                'quantity' => 2,
                'unit_price' => $cake->sale_price,
                'subtotal' => $cakeTotal,
            ]);
            $subtotal += $cakeTotal;

            $sale->update([
                'subtotal' => $subtotal,
                'grand_total' => $subtotal,
                'amount_tendered' => $subtotal
            ]);
        }
    }
}

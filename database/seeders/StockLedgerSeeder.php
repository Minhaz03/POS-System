<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\StockLedger;
use Illuminate\Database\Seeder;

class StockLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@admin.com')->first();
        $userId = $adminUser ? $adminUser->id : null;

        // Fetch products by SKU to link historical stock movements to real products
        $sourdough = Product::where('sku', 'BAK-SOURDOUGH-01')->first();
        $croissant = Product::where('sku', 'BAK-CROISSANT-01')->first();
        $chocolateCake = Product::where('sku', 'BAK-CAKE-CHOC-SL')->first();
        $flour = Product::where('sku', 'RAW-FLOUR-001')->first();
        $box = Product::where('sku', 'PKG-BOX-CAKE-8')->first();

        $movements = [];

        if ($sourdough) {
            $movements[] = [
                'product_id' => $sourdough->id,
                'type' => 'Production (+)',
                'qty' => 30.000,
                'user_id' => $userId,
                'notes' => 'Morning batch production complete.',
                'created_at' => now()->subDay()->setHour(9)->setMinute(30),
            ];
            $movements[] = [
                'product_id' => $sourdough->id,
                'type' => 'POS Sale (-)',
                'qty' => -5.000,
                'user_id' => $userId,
                'notes' => 'Sale receipt INV-08490.',
                'created_at' => now()->subDay()->setHour(10)->setMinute(15),
            ];
        }

        if ($croissant) {
            $movements[] = [
                'product_id' => $croissant->id,
                'type' => 'POS Sale (-)',
                'qty' => -12.000,
                'user_id' => $userId,
                'notes' => 'Bulk sale POS counter.',
                'created_at' => now()->subDay()->setHour(11)->setMinute(30),
            ];
        }

        if ($chocolateCake) {
            $movements[] = [
                'product_id' => $chocolateCake->id,
                'type' => 'Stock Audit (Adj)',
                'qty' => -2.000,
                'user_id' => $userId,
                'notes' => 'Inventory count correction: damaged display slice.',
                'created_at' => now()->subDay()->setHour(13)->setMinute(0),
            ];
        }

        if ($flour) {
            $movements[] = [
                'product_id' => $flour->id,
                'type' => 'Purchase (+)',
                'qty' => 150.000,
                'user_id' => $userId,
                'notes' => 'Received from Premium Flour Mills (PO-2026-001).',
                'created_at' => now()->subDays(2)->setHour(14)->setMinute(0),
            ];
            $movements[] = [
                'product_id' => $flour->id,
                'type' => 'Production (-)',
                'qty' => -10.000,
                'user_id' => $userId,
                'notes' => 'Used in Breads production batch.',
                'created_at' => now()->subDay()->setHour(8)->setMinute(0),
            ];
        }

        if ($box) {
            $movements[] = [
                'product_id' => $box->id,
                'type' => 'Wastage (-)',
                'qty' => -4.000,
                'user_id' => $userId,
                'notes' => 'Damaged by liquid spill in storage area.',
                'created_at' => now()->subDay()->setHour(17)->setMinute(0),
            ];
        }

        foreach ($movements as $movement) {
            StockLedger::create($movement);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use App\Models\ProductionBatch;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        $userId = $user ? $user->id : null;

        // Fetch products seeded by ProductSeeder
        $p1 = Product::where('sku', 'BAK-SOURDOUGH-01')->first();
        $p2 = Product::where('sku', 'BAK-CROISSANT-01')->first();
        $p3 = Product::where('sku', 'BAK-CAKE-CHOC-SL')->first();

        // Create Recipes
        $r1 = Recipe::firstOrCreate(
            ['name' => 'Sourdough Bread'],
            [
                'product_id' => $p1?->id,
                'description' => 'Classic crusty wild yeast sourdough loaf.',
                'category' => 'Bread',
                'prep_time' => '24 hours',
                'bake_time' => '45 mins',
                'yield_qty' => 1,
                'yield_unit' => 'pcs',
                'estimated_cost' => 1.50,
                'is_active' => true,
            ]
        );

        $r2 = Recipe::firstOrCreate(
            ['name' => 'Butter Croissant'],
            [
                'product_id' => $p2?->id,
                'description' => 'Traditional flaky French pastries.',
                'category' => 'Pastry',
                'prep_time' => '4 hours',
                'bake_time' => '20 mins',
                'yield_qty' => 12,
                'yield_unit' => 'pcs',
                'estimated_cost' => 9.60,
                'is_active' => true,
            ]
        );

        $r3 = Recipe::firstOrCreate(
            ['name' => 'Chocolate Muffin'],
            [
                'product_id' => $p3?->id,
                'description' => 'Rich double chocolate muffins with fudge chunks.',
                'category' => 'Cake',
                'prep_time' => '30 mins',
                'bake_time' => '25 mins',
                'yield_qty' => 6,
                'yield_unit' => 'pcs',
                'estimated_cost' => 4.80,
                'is_active' => true,
            ]
        );

        // Create Production Batches
        ProductionBatch::firstOrCreate(
            ['batch_code' => 'PRD-1029'],
            [
                'recipe_id' => $r1->id,
                'qty' => 40,
                'status' => 'Completed',
                'scheduled_at' => now()->subDays(3)->setHour(6)->setMinute(0)->setSecond(0),
                'completed_at' => now()->subDays(3)->setHour(6)->setMinute(45)->setSecond(0),
                'created_by' => $userId,
            ]
        );

        ProductionBatch::firstOrCreate(
            ['batch_code' => 'PRD-1030'],
            [
                'recipe_id' => $r2->id,
                'qty' => 60,
                'status' => 'Completed',
                'scheduled_at' => now()->subDays(2)->setHour(7)->setMinute(30)->setSecond(0),
                'completed_at' => now()->subDays(2)->setHour(8)->setMinute(0)->setSecond(0),
                'created_by' => $userId,
            ]
        );

        ProductionBatch::firstOrCreate(
            ['batch_code' => 'PRD-1031'],
            [
                'recipe_id' => $r3->id,
                'qty' => 24,
                'status' => 'In Progress',
                'scheduled_at' => now()->setHour(11)->setMinute(30)->setSecond(0),
                'created_by' => $userId,
            ]
        );

        ProductionBatch::firstOrCreate(
            ['batch_code' => 'PRD-1032'],
            [
                'recipe_id' => $r1->id,
                'qty' => 30,
                'status' => 'Scheduled',
                'scheduled_at' => now()->addDay()->setHour(14)->setMinute(0)->setSecond(0),
                'created_by' => $userId,
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\ProductionBatch;

class ProductionBatchSeeder extends Seeder
{
    public function run(): void
    {
        $recipeBun = Recipe::where('name', 'Special Butter Bun Recipe')->first();
        $recipeCake = Recipe::where('name', 'Classic Pound Cake Recipe')->first();
        
        if ($recipeBun) {
            ProductionBatch::create([
                'batch_code' => 'BAT-001',
                'recipe_id' => $recipeBun->id,
                'qty' => 50, // Planned output
                'wastage_qty' => 2, // 2 units wasted
                'status' => 'Completed',
                'scheduled_at' => now()->subDays(2),
            ]);
        }

        if ($recipeCake) {
            ProductionBatch::create([
                'batch_code' => 'BAT-002',
                'recipe_id' => $recipeCake->id,
                'qty' => 20, 
                'wastage_qty' => 0, 
                'status' => 'Completed',
                'scheduled_at' => now()->subDays(1),
            ]);

            ProductionBatch::create([
                'batch_code' => 'BAT-003',
                'recipe_id' => $recipeCake->id,
                'qty' => 15,
                'status' => 'Scheduled',
                'scheduled_at' => now(),
            ]);
        }
    }
}

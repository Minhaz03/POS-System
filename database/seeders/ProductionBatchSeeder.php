<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipeSourdough = \App\Models\Recipe::where('name', 'Sourdough Bread')->first();
        $recipeCroissant = \App\Models\Recipe::where('name', 'Butter Croissant')->first();
        $recipeMuffin = \App\Models\Recipe::where('name', 'Chocolate Muffin')->first();

        if ($recipeSourdough) {
            \App\Models\ProductionBatch::create([
                'batch_number' => 'PRD-1029',
                'recipe_id' => $recipeSourdough->id,
                'qty' => 40,
                'status' => 'Completed',
                'production_date' => now()->subDays(1)->setTime(6, 0),
            ]);
            \App\Models\ProductionBatch::create([
                'batch_number' => 'PRD-1032',
                'recipe_id' => $recipeSourdough->id,
                'qty' => 30,
                'status' => 'Scheduled',
                'production_date' => now()->setTime(14, 0),
            ]);
        }

        if ($recipeCroissant) {
            \App\Models\ProductionBatch::create([
                'batch_number' => 'PRD-1030',
                'recipe_id' => $recipeCroissant->id,
                'qty' => 60,
                'status' => 'Completed',
                'production_date' => now()->subDays(1)->setTime(7, 30),
            ]);
        }

        if ($recipeMuffin) {
            \App\Models\ProductionBatch::create([
                'batch_number' => 'PRD-1031',
                'recipe_id' => $recipeMuffin->id,
                'qty' => 24,
                'status' => 'In Progress',
                'production_date' => now()->setTime(11, 30),
            ]);
        }
    }
}

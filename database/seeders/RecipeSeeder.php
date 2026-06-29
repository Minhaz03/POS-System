<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Unit;
use App\Models\RecipeIngredient;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $bun = Product::where('sku', 'BAK-BUN-01')->first();
        $cake = Product::where('sku', 'BAK-CAKE-POUND')->first();
        
        $flour = Product::where('sku', 'RAW-FLOUR-001')->first();
        $sugar = Product::where('sku', 'RAW-SUGAR-001')->first();
        $butter = Product::where('sku', 'RAW-BUTTER-001')->first();
        $milk = Product::where('sku', 'RAW-MILK-001')->first();

        $unitKg = Unit::where('short_name', 'kg')->first();
        $unitG = Unit::where('short_name', 'g')->first();
        $unitL = Unit::where('short_name', 'L')->first();
        $unitMl = Unit::where('short_name', 'ml')->first();
        
        if ($bun && $flour && $sugar && $butter && $milk) {
            $recipeBun = Recipe::create([
                'product_id' => $bun->id,
                'name' => 'Special Butter Bun Recipe',
                'description' => 'Standard recipe for 1 batch of butter bun.',
                'instructions' => 'Mix flour, sugar, and milk. Add butter at the end. Bake at 180C for 20 mins.',
                'is_active' => true,
                'estimated_cost' => 0
            ]);

            $cost = 0;
            // 500g flour
            $flourCost = ($flour->cost_price / 1000) * 500;
            RecipeIngredient::create([
                'recipe_id' => $recipeBun->id,
                'product_id' => $flour->id,
                'ingredient_name' => $flour->name,
                'quantity' => 500,
                'unit_id' => $unitG->id ?? $unitKg->id,
                'unit_cost' => $flour->cost_price / 1000,
                'subtotal' => $flourCost,
            ]);
            $cost += $flourCost;

            // 100g sugar
            $sugarCost = ($sugar->cost_price / 1000) * 100;
            RecipeIngredient::create([
                'recipe_id' => $recipeBun->id,
                'product_id' => $sugar->id,
                'ingredient_name' => $sugar->name,
                'quantity' => 100,
                'unit_id' => $unitG->id ?? $unitKg->id,
                'unit_cost' => $sugar->cost_price / 1000,
                'subtotal' => $sugarCost,
            ]);
            $cost += $sugarCost;

            // 50g butter
            $butterCost = ($butter->cost_price / 1000) * 50;
            RecipeIngredient::create([
                'recipe_id' => $recipeBun->id,
                'product_id' => $butter->id,
                'ingredient_name' => $butter->name,
                'quantity' => 50,
                'unit_id' => $unitG->id ?? $unitKg->id,
                'unit_cost' => $butter->cost_price / 1000,
                'subtotal' => $butterCost,
            ]);
            $cost += $butterCost;

            // 200ml milk
            $milkCost = ($milk->cost_price / 1000) * 200;
            RecipeIngredient::create([
                'recipe_id' => $recipeBun->id,
                'product_id' => $milk->id,
                'ingredient_name' => $milk->name,
                'quantity' => 200,
                'unit_id' => $unitMl->id ?? $unitL->id,
                'unit_cost' => $milk->cost_price / 1000,
                'subtotal' => $milkCost,
            ]);
            $cost += $milkCost;

            $recipeBun->update(['estimated_cost' => $cost]);
        }

        if ($cake && $flour && $sugar && $butter && $milk) {
            $recipeCake = Recipe::create([
                'product_id' => $cake->id,
                'name' => 'Classic Pound Cake Recipe',
                'description' => '1 pound cake recipe.',
                'instructions' => 'Cream butter and sugar. Add flour and milk. Bake at 160C for 45 mins.',
                'is_active' => true,
                'estimated_cost' => 0
            ]);

            $cost = 0;
            
            $flourCost = ($flour->cost_price / 1000) * 200;
            RecipeIngredient::create([
                'recipe_id' => $recipeCake->id,
                'product_id' => $flour->id,
                'ingredient_name' => $flour->name,
                'quantity' => 200,
                'unit_id' => $unitG->id ?? $unitKg->id,
                'unit_cost' => $flour->cost_price / 1000,
                'subtotal' => $flourCost,
            ]);
            $cost += $flourCost;

            $sugarCost = ($sugar->cost_price / 1000) * 200;
            RecipeIngredient::create([
                'recipe_id' => $recipeCake->id,
                'product_id' => $sugar->id,
                'ingredient_name' => $sugar->name,
                'quantity' => 200,
                'unit_id' => $unitG->id ?? $unitKg->id,
                'unit_cost' => $sugar->cost_price / 1000,
                'subtotal' => $sugarCost,
            ]);
            $cost += $sugarCost;

            $butterCost = ($butter->cost_price / 1000) * 200;
            RecipeIngredient::create([
                'recipe_id' => $recipeCake->id,
                'product_id' => $butter->id,
                'ingredient_name' => $butter->name,
                'quantity' => 200,
                'unit_id' => $unitG->id ?? $unitKg->id,
                'unit_cost' => $butter->cost_price / 1000,
                'subtotal' => $butterCost,
            ]);
            $cost += $butterCost;

            $recipeCake->update(['estimated_cost' => $cost]);
        }
    }
}

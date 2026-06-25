<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Parent Categories
        $bakery = Category::firstOrCreate(['name' => 'Bakery Products'], [
            'description' => 'Finished bread, cakes, and other baked goods.',
            'is_active' => true,
        ]);

        $raw = Category::firstOrCreate(['name' => 'Raw Ingredients'], [
            'description' => 'Flour, sugar, butter, and other baking essentials.',
            'is_active' => true,
        ]);

        $packaging = Category::firstOrCreate(['name' => 'Packaging Materials'], [
            'description' => 'Boxes, bags, and ribbons.',
            'is_active' => true,
        ]);

        // Subcategories for Bakery Products
        Category::firstOrCreate(['name' => 'Breads', 'parent_id' => $bakery->id], [
            'description' => 'Loaves, sourdough, buns, and rolls.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Cakes & Cupcakes', 'parent_id' => $bakery->id], [
            'description' => 'Birthday cakes, chocolate cakes, and muffins.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Pastries', 'parent_id' => $bakery->id], [
            'description' => 'Croissants, danishes, and tarts.',
            'is_active' => true,
        ]);

        // Subcategories for Raw Ingredients
        Category::firstOrCreate(['name' => 'Flours & Grains', 'parent_id' => $raw->id], [
            'description' => 'Wheat, rye, and specialty flours.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Dairy & Eggs', 'parent_id' => $raw->id], [
            'description' => 'Butter, milk, cream, and eggs.',
            'is_active' => true,
        ]);
    }
}

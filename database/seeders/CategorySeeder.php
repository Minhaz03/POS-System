<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Parent Categories
        $bakery = Category::firstOrCreate(['name' => 'Bakery & Sweets'], [
            'description' => 'Finished bread, cakes, sweets and other baked goods.',
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
        Category::firstOrCreate(['name' => 'Breads & Buns', 'parent_id' => $bakery->id], [
            'description' => 'Loaves, butter buns, and rolls.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Cakes & Pastries', 'parent_id' => $bakery->id], [
            'description' => 'Birthday cakes, pound cakes, and pastries.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Biscuits & Toast', 'parent_id' => $bakery->id], [
            'description' => 'Dry cakes, toast biscuits, and cookies.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Local Sweets (Mishti)', 'parent_id' => $bakery->id], [
            'description' => 'Roshgolla, Roshmalai, and local sweets.',
            'is_active' => true,
        ]);

        // Subcategories for Raw Ingredients
        Category::firstOrCreate(['name' => 'Flours & Sugar', 'parent_id' => $raw->id], [
            'description' => 'Maida, atta, and sugar.',
            'is_active' => true,
        ]);

        Category::firstOrCreate(['name' => 'Dairy & Eggs', 'parent_id' => $raw->id], [
            'description' => 'Butter, milk, cream, and eggs.',
            'is_active' => true,
        ]);
    }
}

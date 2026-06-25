<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'BakeMaster', 'description' => 'Professional baking ingredients and mixes.'],
            ['name' => 'Sweet Delight', 'description' => 'Premium sugars, syrups, and sweet toppings.'],
            ['name' => 'Golden Grain', 'description' => 'High quality flours and grains.', 'logo' => 'goldengrain.png'],
            ['name' => 'Puratos', 'description' => 'Global group offering range of ingredients for bakery, patisserie and chocolate.'],
            ['name' => 'Local/Generic', 'description' => 'Non-branded raw ingredients or store-made items.'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand['name']], $brand);
        }
    }
}

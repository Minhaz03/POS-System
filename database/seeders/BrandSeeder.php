<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Bashundhara Group', 'description' => 'Premium baking ingredients and mixes.'],
            ['name' => 'Fresh', 'description' => 'Premium sugars, syrups, and sweet toppings.'],
            ['name' => 'Teer', 'description' => 'High quality flours and grains.', 'logo' => 'teer.png'],
            ['name' => 'Pran', 'description' => 'Global group offering range of ingredients for bakery, patisserie and chocolate.'],
            ['name' => 'Local/Generic', 'description' => 'Non-branded raw ingredients or store-made items.'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand['name']], $brand);
        }
    }
}

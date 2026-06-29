<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        // Base units
        $gram = Unit::firstOrCreate(['name' => 'Gram'], ['name' => 'Gram', 'short_name' => 'g']);
        $ml = Unit::firstOrCreate(['name' => 'Milliliter'], ['name' => 'Milliliter', 'short_name' => 'ml']);
        $pcs = Unit::firstOrCreate(['name' => 'Piece'], ['name' => 'Piece', 'short_name' => 'pcs']);
        
        // Child units
        Unit::firstOrCreate(['name' => 'Kilogram'], [
            'name' => 'Kilogram',
            'short_name' => 'kg',
            'base_unit_id' => $gram->id,
            'operator' => '*',
            'conversion_rate' => 1000
        ]);
        
        Unit::firstOrCreate(['name' => 'Liter'], [
            'name' => 'Liter',
            'short_name' => 'L',
            'base_unit_id' => $ml->id,
            'operator' => '*',
            'conversion_rate' => 1000
        ]);
        
        Unit::firstOrCreate(['name' => 'Bag (50kg)'], [
            'name' => 'Bag (50kg)',
            'short_name' => 'bag',
            'base_unit_id' => $gram->id,
            'operator' => '*',
            'conversion_rate' => 50000
        ]);
        
        Unit::firstOrCreate(['name' => 'Pack'], [
            'name' => 'Pack',
            'short_name' => 'pack',
            'base_unit_id' => $pcs->id,
            'operator' => '*',
            'conversion_rate' => 10
        ]);
    }
}

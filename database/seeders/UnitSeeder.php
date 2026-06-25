<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Gram', 'short_name' => 'g'],
            ['name' => 'Kilogram', 'short_name' => 'kg'],
            ['name' => 'Piece', 'short_name' => 'pcs'],
            ['name' => 'Liter', 'short_name' => 'L'],
            ['name' => 'Pack', 'short_name' => 'pack'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['name' => $unit['name']], $unit);
        }
    }
}

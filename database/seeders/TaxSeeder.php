<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            ['name' => 'No VAT (0%)', 'rate' => 0.00, 'is_default' => false],
            ['name' => 'VAT 5%', 'rate' => 5.00, 'is_default' => false],
            ['name' => 'VAT 7.5%', 'rate' => 7.50, 'is_default' => false],
            ['name' => 'VAT 10%', 'rate' => 10.00, 'is_default' => false],
            ['name' => 'VAT 15%', 'rate' => 15.00, 'is_default' => true],
        ];

        foreach ($taxes as $tax) {
            Tax::firstOrCreate(['name' => $tax['name']], $tax);
        }
    }
}

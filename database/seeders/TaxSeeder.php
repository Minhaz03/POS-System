<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $taxes = [
            ['name' => 'No Tax (0%)', 'rate' => 0.00, 'is_default' => false],
            ['name' => 'VAT 5%', 'rate' => 5.00, 'is_default' => true],
            ['name' => 'VAT 15%', 'rate' => 15.00, 'is_default' => false],
        ];

        foreach ($taxes as $tax) {
            Tax::firstOrCreate(['name' => $tax['name']], $tax);
        }
    }
}

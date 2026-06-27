<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\CustomOrder::create([
            'order_number' => 'ORD-501',
            'customer_name' => 'Rubaiya Islam',
            'details' => '2-tier Chocolate Fudge Wedding Cake with white frosting and roses',
            'delivery_date' => now()->addDays(4)->toDateString(),
            'total_price' => 5500,
            'advance_payment' => 2000,
            'status' => 'Confirmed',
        ]);

        \App\Models\CustomOrder::create([
            'order_number' => 'ORD-502',
            'customer_name' => 'Tahmid Hasan',
            'details' => 'Custom Spider-Man Birthday Cake (Vanilla, 2kg)',
            'delivery_date' => now()->addDays(1)->toDateString(),
            'total_price' => 2500,
            'advance_payment' => 1000,
            'status' => 'In Progress',
        ]);

        \App\Models\CustomOrder::create([
            'order_number' => 'ORD-503',
            'customer_name' => 'Nusrat Jahan',
            'details' => 'Red Velvet anniversary cake (Heart-shaped, 1.5kg)',
            'delivery_date' => now()->addDays(6)->toDateString(),
            'total_price' => 2000,
            'advance_payment' => 0,
            'status' => 'Pending Review',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Walk-in Customer',
                'phone' => '00000000000',
                'email' => 'walkin@example.com',
                'address' => 'N/A',
                'date_of_birth' => null,
                'loyalty_points' => 0,
                'total_spent' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'Rahim Uddin',
                'phone' => '01711111111',
                'email' => 'rahim@example.com',
                'address' => 'Banani, Dhaka',
                'date_of_birth' => '1990-05-15',
                'loyalty_points' => 120,
                'total_spent' => 2400.50,
                'is_active' => true,
            ],
            [
                'name' => 'Fatema Begum',
                'phone' => '01822222222',
                'email' => 'fatema@example.com',
                'address' => 'Mirpur, Dhaka',
                'date_of_birth' => '1995-10-22',
                'loyalty_points' => 80,
                'total_spent' => 1500.00,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['phone' => $customer['phone']], $customer);
        }
    }
}

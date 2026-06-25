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
                'phone' => '0000000000',
                'email' => 'walkin@example.com',
                'address' => 'N/A',
                'date_of_birth' => null,
                'loyalty_points' => 0,
                'total_spent' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'John Doe',
                'phone' => '01711111111',
                'email' => 'john.doe@example.com',
                'address' => '123 Baker Street',
                'date_of_birth' => '1990-05-15',
                'loyalty_points' => 120,
                'total_spent' => 240.50,
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '01822222222',
                'email' => 'jane.smith@example.com',
                'address' => '456 Pastry Avenue',
                'date_of_birth' => '1995-10-22',
                'loyalty_points' => 80,
                'total_spent' => 150.00,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['phone' => $customer['phone']], $customer);
        }
    }
}

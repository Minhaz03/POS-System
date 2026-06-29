<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Bashundhara Group Wholesale',
                'contact_person' => 'Md. Rahman',
                'phone' => '+8801711223344',
                'email' => 'sales@bashundhara.com.bd',
                'address' => 'Bashundhara R/A',
                'city' => 'Dhaka',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'Fresh Food & Beverage',
                'contact_person' => 'Kamrul Hasan',
                'phone' => '+8801811223344',
                'email' => 'sales@meghnagroup.biz',
                'address' => 'Fresh Villa',
                'city' => 'Narayanganj',
                'opening_balance' => 5000.00,
                'current_balance' => 5000.00, // We owe them 5000 TK
                'is_active' => true,
            ],
            [
                'name' => 'Aarong Dairy Distributors',
                'contact_person' => 'Shafiqul Islam',
                'phone' => '+8801911223344',
                'email' => 'contact@aarongdairy.com',
                'address' => 'Tejgaon Industrial Area',
                'city' => 'Dhaka',
                'opening_balance' => 0.00,
                'current_balance' => -1000.00, // They owe us 1000 TK
                'is_active' => true,
            ],
            [
                'name' => 'Eco Packaging Suppliers BD',
                'contact_person' => 'Parvez Hossain',
                'phone' => '+8801511223344',
                'email' => 'info@ecopack.com.bd',
                'address' => 'Chawkbazar',
                'city' => 'Dhaka',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['name' => $supplier['name']], $supplier);
        }
    }
}

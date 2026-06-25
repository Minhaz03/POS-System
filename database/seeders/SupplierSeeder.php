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
                'name' => 'Wholesale Flour & Co.',
                'contact_person' => 'Robert Floury',
                'phone' => '+1555123456',
                'email' => 'robert@wholesale-flour.com',
                'address' => '12 Mill Rd',
                'city' => 'Minneapolis',
                'opening_balance' => 0.00,
                'current_balance' => 0.00,
                'is_active' => true,
            ],
            [
                'name' => 'Sweet Sugars Ltd.',
                'contact_person' => 'Jane Cane',
                'phone' => '+1555234567',
                'email' => 'sales@sweetsugars.com',
                'address' => '45 Sugar Cane Lane',
                'city' => 'Miami',
                'opening_balance' => 500.00,
                'current_balance' => 500.00, // We owe them $500
                'is_active' => true,
            ],
            [
                'name' => 'Premium Dairy Distributors',
                'contact_person' => 'Michael Milk',
                'phone' => '+1555345678',
                'email' => 'michael@premiumdairy.com',
                'address' => '78 Dairy Pasteur',
                'city' => 'Wisconsin Dells',
                'opening_balance' => 0.00,
                'current_balance' => -100.00, // They owe us $100 (overpayment)
                'is_active' => true,
            ],
            [
                'name' => 'Eco Packaging Supplies',
                'contact_person' => 'Patricia Box',
                'phone' => '+1555456789',
                'email' => 'patricia@ecopack.com',
                'address' => '90 Green Valley St',
                'city' => 'Portland',
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

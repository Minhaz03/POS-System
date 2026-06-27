<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have an admin user to associate with created_by
        $user = User::first();
        $userId = $user ? $user->id : null;

        // Register customers first
        $c1 = Customer::firstOrCreate(
            ['phone' => '01711223344'],
            [
                'name' => 'Rubaiya Islam',
                'email' => 'rubaiya@example.com',
                'address' => 'Dhaka, Bangladesh',
                'loyalty_points' => 50,
                'total_spent' => 5500.00,
                'is_active' => true,
            ]
        );

        $c2 = Customer::firstOrCreate(
            ['phone' => '01811223344'],
            [
                'name' => 'Tahmid Hasan',
                'email' => 'tahmid@example.com',
                'address' => 'Chittagong, Bangladesh',
                'loyalty_points' => 25,
                'total_spent' => 2500.00,
                'is_active' => true,
            ]
        );

        $c3 = Customer::firstOrCreate(
            ['phone' => '01911223344'],
            [
                'name' => 'Nusrat Jahan',
                'email' => 'nusrat@example.com',
                'address' => 'Sylhet, Bangladesh',
                'loyalty_points' => 10,
                'total_spent' => 0.00,
                'is_active' => true,
            ]
        );

        // Create Custom Orders
        CustomOrder::firstOrCreate(
            ['order_number' => 'ORD-00001'],
            [
                'customer_id' => $c1->id,
                'details' => '2-tier Chocolate Fudge Wedding Cake with white frosting and roses',
                'price' => 5500.00,
                'advance' => 2000.00,
                'status' => 'Confirmed',
                'delivery_date' => now()->addDays(5)->format('Y-m-d'),
                'created_by' => $userId,
            ]
        );

        CustomOrder::firstOrCreate(
            ['order_number' => 'ORD-00002'],
            [
                'customer_id' => $c2->id,
                'details' => 'Custom Spider-Man Birthday Cake (Vanilla, 2kg)',
                'price' => 2500.00,
                'advance' => 1000.00,
                'status' => 'In Progress',
                'delivery_date' => now()->addDays(2)->format('Y-m-d'),
                'created_by' => $userId,
            ]
        );

        CustomOrder::firstOrCreate(
            ['order_number' => 'ORD-00003'],
            [
                'customer_id' => $c3->id,
                'details' => 'Red Velvet anniversary cake (Heart-shaped, 1.5kg)',
                'price' => 2000.00,
                'advance' => 0.00,
                'status' => 'Pending',
                'delivery_date' => now()->addDays(7)->format('Y-m-d'),
                'created_by' => $userId,
            ]
        );
    }
}

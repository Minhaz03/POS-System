<?php

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomOrder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SettingsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(SettingsSeeder::class);
});

test('authenticated user can view custom orders index page', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Test Customer',
        'phone' => '01700000000',
        'email' => 'test@customer.com',
    ]);

    $order = CustomOrder::create([
        'customer_id' => $customer->id,
        'details' => 'Test Spec for Custom Cake',
        'price' => 1200.00,
        'advance' => 500.00,
        'delivery_date' => now()->addDays(3)->format('Y-m-d'),
        'status' => 'Pending',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.custom-orders'));
    $response->assertOk();
    $response->assertSee('Test Customer');
    $response->assertSee('Test Spec for Custom Cake');
    $response->assertSee('ORD-00001');
});

test('authenticated user can perform custom order store operation', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'John Doe Cake Lover',
        'phone' => '01800000000',
    ]);

    $response = $this->actingAs($user)->post(route('dashboard.custom-orders.store'), [
        'customer_id' => $customer->id,
        'details' => 'Large Wedding Cake, 3-tier, Strawberry flavour',
        'price' => 4500.00,
        'advance' => 1500.00,
        'delivery_date' => now()->addDays(5)->format('Y-m-d'),
        'status' => 'Confirmed',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard.custom-orders'));
    $this->assertDatabaseHas('custom_orders', [
        'details' => 'Large Wedding Cake, 3-tier, Strawberry flavour',
        'price' => 4500.00,
        'advance' => 1500.00,
        'status' => 'Confirmed',
        'created_by' => $user->id,
    ]);
});

test('authenticated user can view custom order slip detailed page', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Jane Anniversary',
        'phone' => '01900000000',
    ]);

    $order = CustomOrder::create([
        'customer_id' => $customer->id,
        'details' => 'Custom Birthday Cupcakes',
        'price' => 800.00,
        'advance' => 200.00,
        'delivery_date' => now()->addDays(2)->format('Y-m-d'),
        'status' => 'In Progress',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.custom-orders.show', $order));
    $response->assertOk();
    $response->assertSee('Jane Anniversary');
    $response->assertSee('Custom Birthday Cupcakes');
    $response->assertSee('Balance Due');
});

test('authenticated user can edit and update custom order', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Old Customer',
        'phone' => '01600000000',
    ]);
    
    $newCustomer = Customer::create([
        'name' => 'New Customer',
        'phone' => '01500000000',
    ]);

    $order = CustomOrder::create([
        'customer_id' => $customer->id,
        'details' => 'Old spec',
        'price' => 1000.00,
        'advance' => 0.00,
        'delivery_date' => now()->addDays(1)->format('Y-m-d'),
        'status' => 'Pending',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->put(route('dashboard.custom-orders.update', $order), [
        'customer_id' => $newCustomer->id,
        'details' => 'Updated specs for cupcakes',
        'price' => 1500.00,
        'advance' => 500.00,
        'delivery_date' => now()->addDays(4)->format('Y-m-d'),
        'status' => 'Completed',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard.custom-orders'));
    $this->assertDatabaseHas('custom_orders', [
        'id' => $order->id,
        'customer_id' => $newCustomer->id,
        'details' => 'Updated specs for cupcakes',
        'price' => 1500.00,
        'advance' => 500.00,
        'status' => 'Completed',
    ]);
});

test('authenticated user can delete custom order using soft deletes', function () {
    $user = User::factory()->create();
    $customer = Customer::create([
        'name' => 'Cancellable Customer',
        'phone' => '01300000000',
    ]);

    $order = CustomOrder::create([
        'customer_id' => $customer->id,
        'details' => 'To be deleted order',
        'price' => 2000.00,
        'advance' => 1000.00,
        'delivery_date' => now()->addDays(5)->format('Y-m-d'),
        'status' => 'Cancelled',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete(route('dashboard.custom-orders.destroy', $order));
    $response->assertRedirect(route('dashboard.custom-orders'));
    
    $this->assertSoftDeleted($order);
});

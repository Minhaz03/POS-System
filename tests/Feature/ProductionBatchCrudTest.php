<?php

use App\Models\User;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\StockLedger;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SettingsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->seed(SettingsSeeder::class);
});

test('authenticated user can view production batches index page', function () {
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'name' => 'Sourdough Country Loaf',
        'yield_qty' => 1,
    ]);

    $batch = ProductionBatch::create([
        'recipe_id' => $recipe->id,
        'qty' => 10.000,
        'status' => 'Scheduled',
        'scheduled_at' => now()->addDay(),
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.production'));
    $response->assertOk();
    $response->assertSee('Sourdough Country Loaf');
    $response->assertSee('PRD-1001');
});

test('authenticated user can store scheduled production batch', function () {
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'name' => 'Croissant Supreme',
        'yield_qty' => 12,
    ]);

    $response = $this->actingAs($user)->post(route('dashboard.production.store'), [
        'recipe_id' => $recipe->id,
        'qty' => 24.000,
        'status' => 'Scheduled',
        'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard.production'));
    $this->assertDatabaseHas('production_batches', [
        'recipe_id' => $recipe->id,
        'qty' => 24.000,
        'status' => 'Scheduled',
        'created_by' => $user->id,
    ]);
});

test('storing completed production batch adjusts product stock and logs ledger', function () {
    $user = User::factory()->create();
    $product = Product::create([
        'name' => 'Baguette Royale',
        'sku' => 'BAG-ROY-01',
        'cost_price' => 1.00,
        'sale_price' => 3.00,
        'stock_qty' => 10.000,
    ]);

    $recipe = Recipe::create([
        'name' => 'Baguette',
        'product_id' => $product->id,
        'yield_qty' => 1,
    ]);

    $response = $this->actingAs($user)->post(route('dashboard.production.store'), [
        'recipe_id' => $recipe->id,
        'qty' => 15.000,
        'status' => 'Completed',
        'scheduled_at' => now()->format('Y-m-d H:i:s'),
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard.production'));

    // Check product stock was incremented: 10 + 15 = 25
    $product->refresh();
    expect($product->stock_qty)->toEqual(25.000);

    // Check stock ledger logged
    $this->assertDatabaseHas('stock_ledgers', [
        'product_id' => $product->id,
        'type' => 'Production (+)',
        'qty' => 15.000,
        'user_id' => $user->id,
    ]);
});

test('authenticated user can complete scheduled production batch', function () {
    $user = User::factory()->create();
    $product = Product::create([
        'name' => 'Glazed Donut',
        'sku' => 'DON-GLZ-01',
        'cost_price' => 0.50,
        'sale_price' => 1.50,
        'stock_qty' => 5.000,
    ]);

    $recipe = Recipe::create([
        'name' => 'Donuts',
        'product_id' => $product->id,
        'yield_qty' => 12,
    ]);

    $batch = ProductionBatch::create([
        'recipe_id' => $recipe->id,
        'qty' => 36.000,
        'status' => 'Scheduled',
        'scheduled_at' => now()->addDays(1),
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->patch(route('dashboard.production-batches.complete', $batch));
    $response->assertRedirect();

    $batch->refresh();
    expect($batch->status)->toBe('Completed');
    expect($batch->completed_at)->not->toBeNull();

    // Check product stock was incremented: 5 + 36 = 41
    $product->refresh();
    expect($product->stock_qty)->toEqual(41.000);

    // Check StockLedger
    $this->assertDatabaseHas('stock_ledgers', [
        'product_id' => $product->id,
        'type' => 'Production (+)',
        'qty' => 36.000,
    ]);
});

test('authenticated user can cancel production batch', function () {
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'name' => 'Muffin Classic',
        'yield_qty' => 6,
    ]);

    $batch = ProductionBatch::create([
        'recipe_id' => $recipe->id,
        'qty' => 12.000,
        'status' => 'In Progress',
        'scheduled_at' => now(),
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->patch(route('dashboard.production-batches.cancel', $batch));
    $response->assertRedirect();

    $batch->refresh();
    expect($batch->status)->toBe('Cancelled');
});

test('completed production batches cannot be cancelled or deleted', function () {
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'name' => 'Cake Wedding',
        'yield_qty' => 1,
    ]);

    $batch = ProductionBatch::create([
        'recipe_id' => $recipe->id,
        'qty' => 1.000,
        'status' => 'Completed',
        'scheduled_at' => now(),
        'completed_at' => now(),
        'created_by' => $user->id,
    ]);

    // 1. Try to cancel completed batch
    $response = $this->actingAs($user)->patch(route('dashboard.production-batches.cancel', $batch));
    $response->assertSessionHas('error');
    $batch->refresh();
    expect($batch->status)->toBe('Completed');

    // 2. Try to delete completed batch
    $response = $this->actingAs($user)->delete(route('dashboard.production-batches.destroy', $batch));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('production_batches', ['id' => $batch->id, 'deleted_at' => null]);
});

test('authenticated user can delete non-completed production batch', function () {
    $user = User::factory()->create();
    $recipe = Recipe::create([
        'name' => 'Scone Jam',
        'yield_qty' => 8,
    ]);

    $batch = ProductionBatch::create([
        'recipe_id' => $recipe->id,
        'qty' => 16.000,
        'status' => 'Scheduled',
        'scheduled_at' => now()->addDay(),
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete(route('dashboard.production-batches.destroy', $batch));
    $response->assertRedirect(route('dashboard.production'));

    $this->assertSoftDeleted($batch);
});

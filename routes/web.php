<?php

use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\StockLedgerController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard Subpages
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::patch('Products/{product}/toggle-stock', [ProductController::class, 'toggleStock'])->name('products.toggle-stock');
    
    // CRUD Resources with custom route names matching the UI sidebar
    Route::resource('Products', ProductController::class)->parameters([
        'Products' => 'product'
    ])->names([
        'index' => 'products',
        'create' => 'products.create',
        'store' => 'products.store',
        'show' => 'products.show',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);
    
    Route::resource('Categories', CategoryController::class)->parameters([
        'Categories' => 'category'
    ])->names([
        'index' => 'categories',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);

    Route::resource('Suppliers', SupplierController::class)->parameters([
        'Suppliers' => 'supplier'
    ])->names([
        'index' => 'suppliers',
        'create' => 'suppliers.create',
        'store' => 'suppliers.store',
        'show' => 'suppliers.show',
        'edit' => 'suppliers.edit',
        'update' => 'suppliers.update',
        'destroy' => 'suppliers.destroy',
    ]);

    Route::resource('Customers', CustomerController::class)->parameters([
        'Customers' => 'customer'
    ])->names([
        'index' => 'customers',
        'create' => 'customers.create',
        'store' => 'customers.store',
        'show' => 'customers.show',
        'edit' => 'customers.edit',
        'update' => 'customers.update',
        'destroy' => 'customers.destroy',
    ]);

    Route::resource('Units', UnitController::class)->parameters([
        'Units' => 'unit'
    ])->names([
        'index' => 'units',
        'create' => 'units.create',
        'store' => 'units.store',
        'show' => 'units.show',
        'edit' => 'units.edit',
        'update' => 'units.update',
        'destroy' => 'units.destroy',
    ]);

    Route::resource('Brands', BrandController::class)->parameters([
        'Brands' => 'brand'
    ])->names([
        'index' => 'brands',
        'create' => 'brands.create',
        'store' => 'brands.store',
        'show' => 'brands.show',
        'edit' => 'brands.edit',
        'update' => 'brands.update',
        'destroy' => 'brands.destroy',
    ]);

    Route::get('/Stock-Ledger', [StockLedgerController::class, 'stockLedger'])->name('stock-ledger');
    Route::post('/Stock-Ledger/adjust', [StockLedgerController::class, 'adjustStock'])->name('stock-ledger.adjust');
    Route::get('/Stock-Ledger/export', [StockLedgerController::class, 'exportExcel'])->name('stock-ledger.export');
    Route::post('Purchases/{purchase}/receive', [PurchaseController::class, 'receive'])->name('purchases.receive');
    Route::resource('Purchases', PurchaseController::class)->parameters([
        'Purchases' => 'purchase'
    ])->names([
        'index' => 'purchases',
        'create' => 'purchases.create',
        'store' => 'purchases.store',
        'show' => 'purchases.show',
        'edit' => 'purchases.edit',
        'update' => 'purchases.update',
        'destroy' => 'purchases.destroy',
    ]);
    Route::get('/POS-Terminal', [PosController::class, 'posTerminal'])->name('pos-terminal');
    Route::resource('Sales', SaleController::class)->parameters(['Sales' => 'sale'])->except(['create', 'store'])->names([
        'index'   => 'sales',
        'show'    => 'sales.show',
        'edit'    => 'sales.edit',
        'update'  => 'sales.update',
        'destroy' => 'sales.destroy',
    ]);
    Route::resource('Recipes', RecipeController::class)->parameters([
        'Recipes' => 'recipe'
    ])->names([
        'index'   => 'recipes',
        'create'  => 'recipes.create',
        'store'   => 'recipes.store',
        'show'    => 'recipes.show',
        'edit'    => 'recipes.edit',
        'update'  => 'recipes.update',
        'destroy' => 'recipes.destroy',
    ]);
    Route::get('/Production', [ProductionController::class, 'production'])->name('production');
    Route::get('/Custom-Orders', [CustomOrderController::class, 'customOrders'])->name('custom-orders');
    Route::get('/Analytics', [AnalyticsController::class, 'analytics'])->name('analytics');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // General Settings & Profile
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Module Control Panel
    Route::middleware('can:modules.manage')->group(function () {
        Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
        Route::post('/modules/infrastructure/{module}', [ModuleController::class, 'toggleInfrastructure'])->name('modules.toggle-infrastructure');
        Route::post('/modules/business-type', [ModuleController::class, 'setBusinessType'])->name('modules.set-business-type');
    });
});

require __DIR__.'/auth.php';

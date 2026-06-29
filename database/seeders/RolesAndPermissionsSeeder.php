<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Core Auth / Users
            'users.view', 'users.create', 'users.edit', 'users.delete',

            // Roles & Permissions
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',

            // Core Inventory & Products
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'categories.manage', 'units.manage', 'taxes.manage', 'brands.manage',

            // Stock Management
            'stock.view', 'stock.adjust', 'stock.alerts',

            // Suppliers & Purchases
            'suppliers.manage', 'purchases.manage', 'purchases.create', 'purchases.view',

            // POS & Sales
            'pos.access', 'pos.sell', 'customers.manage', 'sales.view', 'sales.return',

            // Modules & Settings (Super Admin only)
            'settings.manage', 'settings.view', 'modules.manage', 'activity.logs',

            // Reports
            'reports.view', 'reports.export',

            // Warehouse Module (Optional)
            'warehouse.view', 'warehouse.manage', 'warehouse.transfer',

            // Branch Module (Optional)
            'branch.view', 'branch.manage',

            // Bakery Module (Optional)
            'recipes.manage', 'production.manage', 'production.create', 'production.view',
            'supply.orders.manage', 'custom.orders.manage',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::findOrCreate($permission);
        }

        // Create Roles and Assign Permissions
        
        // Super Admin - gets all permissions
        $superAdminRole = \Spatie\Permission\Models\Role::findOrCreate('Super Admin');
        $superAdminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Admin
        $adminRole = \Spatie\Permission\Models\Role::findOrCreate('Admin');
        $adminRole->syncPermissions([
            'users.view', 'users.create', 'users.edit',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'categories.manage', 'units.manage', 'taxes.manage', 'brands.manage',
            'stock.view', 'stock.adjust', 'stock.alerts',
            'suppliers.manage', 'purchases.manage', 'purchases.create', 'purchases.view',
            'pos.access', 'pos.sell', 'customers.manage', 'sales.view', 'sales.return',
            'settings.manage', 'activity.logs', 'reports.view', 'reports.export',
            'warehouse.view', 'warehouse.transfer',
            'branch.view',
            'recipes.manage', 'production.view', 'production.create', 'production.manage',
            'supply.orders.manage', 'custom.orders.manage',
        ]);

        // Manager
        $managerRole = \Spatie\Permission\Models\Role::findOrCreate('Manager');
        $managerRole->syncPermissions([
            'products.view', 'products.create', 'products.edit',
            'categories.manage', 'units.manage', 'brands.manage',
            'stock.view', 'stock.adjust', 'stock.alerts',
            'suppliers.manage', 'purchases.view', 'purchases.create',
            'pos.access', 'pos.sell', 'customers.manage', 'sales.view',
            'reports.view',
            'warehouse.view', 'warehouse.transfer',
            'recipes.manage', 'production.view', 'production.create',
            'supply.orders.manage', 'custom.orders.manage',
        ]);

        // Cashier
        $cashierRole = \Spatie\Permission\Models\Role::findOrCreate('Cashier');
        $cashierRole->syncPermissions([
            'pos.access', 'pos.sell', 'customers.manage', 'products.view',
            'sales.view', 'custom.orders.manage',
        ]);

        // Warehouse Staff
        $warehouseStaffRole = \Spatie\Permission\Models\Role::findOrCreate('Warehouse Staff');
        $warehouseStaffRole->syncPermissions([
            'stock.view', 'warehouse.view', 'warehouse.transfer', 'products.view'
        ]);

        // Production Staff
        $productionStaffRole = \Spatie\Permission\Models\Role::findOrCreate('Production Staff');
        $productionStaffRole->syncPermissions([
            'recipes.manage', 'production.view', 'production.create', 'production.manage', 'products.view'
        ]);
        
        // Seed default Super Admin User if not exists
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($superAdminRole);
    }
}

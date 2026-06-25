<?php

return [
    'core' => [
        'inventory' => [
            'name' => 'Inventory Management',
            'description' => 'Manage products, stock ledger, categories, and brands.',
            'icon' => 'bi-box-seam',
        ],
        'pos' => [
            'name' => 'Point of Sale (POS)',
            'description' => 'Interactive screen to create quick sales and walk-in purchases.',
            'icon' => 'bi-calculator',
        ],
        'reports' => [
            'name' => 'Reporting & Analytics',
            'description' => 'Consolidated sales, profit & loss, stock, and customer ledgers.',
            'icon' => 'bi-graph-up-arrow',
        ],
        'users' => [
            'name' => 'User & Role Management',
            'description' => 'Manage system users, cashier PIN logins, and assign roles.',
            'icon' => 'bi-people',
        ],
    ],
    'infrastructure' => [
        'warehouse' => [
            'name' => 'Warehouse Management',
            'description' => 'Manage multiple physical warehouses and stock transfers.',
            'icon' => 'bi-building-down',
        ],
        'branch' => [
            'name' => 'Branch Management',
            'description' => 'Scope sales, purchases, and cash registers to distinct branches.',
            'icon' => 'bi-diagram-3',
        ],
    ],
    'business_type' => [
        'bakery' => [
            'name' => 'Bakery Module',
            'description' => 'Recipe management, raw ingredient tracking, daily fresh production schedules.',
            'icon' => 'bi-egg-fried',
        ],
        'electronics' => [
            'name' => 'Electronics Module',
            'description' => 'Serial number tracking, warranty management, and tech spec sheets.',
            'icon' => 'bi-cpu',
        ],
        'grocery' => [
            'name' => 'Grocery Module',
            'description' => 'Batch management, weight scale integrations, and fast checkout grid.',
            'icon' => 'bi-cart',
        ],
        'pharmacy' => [
            'name' => 'Pharmacy Module',
            'description' => 'Drug batch expiries, prescription scanning, and generic formula references.',
            'icon' => 'bi-capsule',
        ],
        'production' => [
            'name' => 'Manufacturing Module',
            'description' => 'Multi-stage production flows, Bill of Materials (BOM), and factory routing.',
            'icon' => 'bi-gear',
        ],
    ],
];

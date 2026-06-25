<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Business Info
            ['key' => 'business_name',    'value' => 'My POS Business', 'type' => 'string',  'group' => 'general'],
            ['key' => 'business_address', 'value' => '',                'type' => 'string',  'group' => 'general'],
            ['key' => 'business_phone',   'value' => '',                'type' => 'string',  'group' => 'general'],
            ['key' => 'business_email',   'value' => '',                'type' => 'string',  'group' => 'general'],
            ['key' => 'currency_code',    'value' => 'BDT',             'type' => 'string',  'group' => 'general'],
            ['key' => 'currency_symbol',  'value' => '৳',              'type' => 'string',  'group' => 'general'],

            // Module States
            ['key' => 'module_warehouse_enabled',  'value' => '0',     'type' => 'boolean', 'group' => 'modules'],
            ['key' => 'module_branch_enabled',     'value' => '0',     'type' => 'boolean', 'group' => 'modules'],
            ['key' => 'active_business_type',      'value' => 'bakery','type' => 'string',  'group' => 'modules'],
        ];

        foreach ($defaults as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type'], 'group' => $setting['group']]
            );
        }
    }
}

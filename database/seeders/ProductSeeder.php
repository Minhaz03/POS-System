<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get related models for mapping
        $catBreads = Category::where('name', 'Breads & Buns')->first();
        $catCakes = Category::where('name', 'Cakes & Pastries')->first();
        $catBiscuits = Category::where('name', 'Biscuits & Toast')->first();
        $catSweets = Category::where('name', 'Local Sweets (Mishti)')->first();
        $catFlour = Category::where('name', 'Flours & Sugar')->first();
        $catDairy = Category::where('name', 'Dairy & Eggs')->first();
        $catPackaging = Category::where('name', 'Packaging Materials')->first();

        $brandTeer = Brand::where('name', 'Teer')->first();
        $brandFresh = Brand::where('name', 'Fresh')->first();
        $brandBashundhara = Brand::where('name', 'Bashundhara Group')->first();
        $brandPran = Brand::where('name', 'Pran')->first();
        $brandLocal = Brand::where('name', 'Local/Generic')->first();

        $unitKg = Unit::where('short_name', 'kg')->first();
        $unitG = Unit::where('short_name', 'g')->first();
        $unitPcs = Unit::where('short_name', 'pcs')->first();
        $unitL = Unit::where('short_name', 'L')->first();
        $unitPack = Unit::where('short_name', 'pack')->first();

        $taxDefault = Tax::where('is_default', true)->first() ?? Tax::first();
        $taxNone = Tax::where('rate', 0)->first();

        $products = [
            // Raw materials
            [
                'name' => 'Teer Maida (Flour)',
                'sku' => 'RAW-FLOUR-001',
                'barcode' => '100000000001',
                'category_id' => $catFlour?->id,
                'brand_id' => $brandTeer?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxNone?->id ?? $taxDefault?->id,
                'description' => 'High quality refined wheat flour.',
                'cost_price' => 60.00,
                'sale_price' => 70.00,
                'mrp_price' => 75.00,
                'stock_qty' => 150.000,
                'alert_qty' => 20.000,
                'reorder_qty' => 100.000,
                'is_pos_enabled' => false,
                'product_type' => 'raw_material',
            ],
            [
                'name' => 'Fresh Fine Sugar',
                'sku' => 'RAW-SUGAR-001',
                'barcode' => '100000000002',
                'category_id' => $catFlour?->id,
                'brand_id' => $brandFresh?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxNone?->id ?? $taxDefault?->id,
                'description' => 'Extra fine granulated pure cane sugar.',
                'cost_price' => 130.00,
                'sale_price' => 140.00,
                'mrp_price' => 145.00,
                'stock_qty' => 80.000,
                'alert_qty' => 15.000,
                'reorder_qty' => 50.000,
                'is_pos_enabled' => false,
                'product_type' => 'raw_material',
            ],
            [
                'name' => 'Pran Dairy Butter',
                'sku' => 'RAW-BUTTER-001',
                'barcode' => '100000000003',
                'category_id' => $catDairy?->id,
                'brand_id' => $brandPran?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Pure cow milk butter.',
                'cost_price' => 200.00,
                'sale_price' => 250.00,
                'mrp_price' => 260.00,
                'stock_qty' => 45.000,
                'alert_qty' => 10.000,
                'reorder_qty' => 30.000,
                'is_pos_enabled' => false,
                'product_type' => 'raw_material',
            ],
            [
                'name' => 'Pran UHT Milk',
                'sku' => 'RAW-MILK-001',
                'barcode' => '100000000004',
                'category_id' => $catDairy?->id,
                'brand_id' => $brandPran?->id,
                'unit_id' => $unitL?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Fresh pasteurized whole cow milk.',
                'cost_price' => 85.00,
                'sale_price' => 90.00,
                'mrp_price' => 95.00,
                'stock_qty' => 60.000,
                'alert_qty' => 15.000,
                'reorder_qty' => 40.000,
                'is_pos_enabled' => false,
                'product_type' => 'raw_material',
            ],

            // Finished products
            [
                'name' => 'Special Butter Bun',
                'sku' => 'BAK-BUN-01',
                'barcode' => '200000000001',
                'category_id' => $catBreads?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Soft and sweet butter bun.',
                'cost_price' => 15.00,
                'sale_price' => 25.00,
                'mrp_price' => 25.00,
                'stock_qty' => 50.000,
                'alert_qty' => 10.000,
                'reorder_qty' => 30.000,
                'is_pos_enabled' => true,
                'product_type' => 'finished_product',
            ],
            [
                'name' => 'Dry Cake / Toast Biscuit',
                'sku' => 'BAK-TOAST-01',
                'barcode' => '200000000002',
                'category_id' => $catBiscuits?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPack?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Crispy sweet toast biscuits for tea.',
                'cost_price' => 45.00,
                'sale_price' => 60.00,
                'mrp_price' => 60.00,
                'stock_qty' => 40.000,
                'alert_qty' => 10.000,
                'reorder_qty' => 20.000,
                'is_pos_enabled' => true,
                'product_type' => 'finished_product',
            ],
            [
                'name' => 'Classic Pound Cake',
                'sku' => 'BAK-CAKE-POUND',
                'barcode' => '200000000003',
                'category_id' => $catCakes?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Rich and buttery pound cake.',
                'cost_price' => 110.00,
                'sale_price' => 160.00,
                'mrp_price' => 160.00,
                'stock_qty' => 16.000,
                'alert_qty' => 4.000,
                'reorder_qty' => 8.000,
                'is_pos_enabled' => true,
                'product_type' => 'finished_product',
            ],
            [
                'name' => 'Premium Roshgolla',
                'sku' => 'BAK-SWEET-ROSH',
                'barcode' => '200000000004',
                'category_id' => $catSweets?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Traditional Bangladeshi Roshgolla sweet.',
                'cost_price' => 250.00,
                'sale_price' => 350.00,
                'mrp_price' => 350.00,
                'stock_qty' => 10.000,
                'alert_qty' => 2.000,
                'reorder_qty' => 5.000,
                'is_pos_enabled' => true,
                'product_type' => 'finished_product',
            ],
            [
                'name' => 'Cake/Sweet Packaging Box',
                'sku' => 'PKG-BOX-CAKE-8',
                'barcode' => '300000000001',
                'category_id' => $catPackaging?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'White corrugated cardboard box for cakes and sweets.',
                'cost_price' => 12.00,
                'sale_price' => 15.00,
                'mrp_price' => 15.00,
                'stock_qty' => 200.000,
                'alert_qty' => 30.000,
                'reorder_qty' => 100.000,
                'is_pos_enabled' => false,
                'product_type' => 'raw_material',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}

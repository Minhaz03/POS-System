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
        $catBreads = Category::where('name', 'Breads')->first();
        $catCakes = Category::where('name', 'Cakes & Cupcakes')->first();
        $catPastries = Category::where('name', 'Pastries')->first();
        $catFlour = Category::where('name', 'Flours & Grains')->first();
        $catDairy = Category::where('name', 'Dairy & Eggs')->first();
        $catPackaging = Category::where('name', 'Packaging Materials')->first();

        $brandGrains = Brand::where('name', 'Golden Grain')->first();
        $brandMaster = Brand::where('name', 'BakeMaster')->first();
        $brandSweet = Brand::where('name', 'Sweet Delight')->first();
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
                'name' => 'Premium Bread Flour',
                'sku' => 'RAW-FLOUR-001',
                'barcode' => '100000000001',
                'category_id' => $catFlour?->id,
                'brand_id' => $brandGrains?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxNone?->id ?? $taxDefault?->id,
                'description' => 'Unbleached white bread flour, high protein content.',
                'cost_price' => 1.20,
                'sale_price' => 1.80,
                'mrp_price' => 2.00,
                'stock_qty' => 150.000,
                'alert_qty' => 20.000,
                'reorder_qty' => 100.000,
                'is_pos_enabled' => false,
                'is_bakery_item' => false,
            ],
            [
                'name' => 'Fine White Sugar',
                'sku' => 'RAW-SUGAR-001',
                'barcode' => '100000000002',
                'category_id' => $catFlour?->parent_id, // Raw ingredients parent if category is sub
                'brand_id' => $brandSweet?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Extra fine granulated pure cane sugar.',
                'cost_price' => 0.90,
                'sale_price' => 1.30,
                'mrp_price' => 1.50,
                'stock_qty' => 80.000,
                'alert_qty' => 15.000,
                'reorder_qty' => 50.000,
                'is_pos_enabled' => false,
                'is_bakery_item' => false,
            ],
            [
                'name' => 'Unsalted Butter',
                'sku' => 'RAW-BUTTER-001',
                'barcode' => '100000000003',
                'category_id' => $catDairy?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitKg?->id,
                'tax_id' => $taxDefault?->id,
                'description' => '82% fat unsalted sweet cream butter.',
                'cost_price' => 6.50,
                'sale_price' => 8.50,
                'mrp_price' => 9.00,
                'stock_qty' => 45.000,
                'alert_qty' => 10.000,
                'reorder_qty' => 30.000,
                'is_pos_enabled' => false,
                'is_bakery_item' => false,
            ],
            [
                'name' => 'Whole Milk',
                'sku' => 'RAW-MILK-001',
                'barcode' => '100000000004',
                'category_id' => $catDairy?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitL?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Fresh pasteurized whole cow milk.',
                'cost_price' => 1.00,
                'sale_price' => 1.50,
                'mrp_price' => 1.60,
                'stock_qty' => 60.000,
                'alert_qty' => 15.000,
                'reorder_qty' => 40.000,
                'is_pos_enabled' => false,
                'is_bakery_item' => false,
            ],

            // Finished products
            [
                'name' => 'Sourdough Country Loaf',
                'sku' => 'BAK-SOURDOUGH-01',
                'barcode' => '200000000001',
                'category_id' => $catBreads?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Classic crusty sourdough bread loaf made with wild yeast.',
                'cost_price' => 1.50,
                'sale_price' => 4.50,
                'mrp_price' => 4.50,
                'stock_qty' => 24.000,
                'alert_qty' => 5.000,
                'reorder_qty' => 10.000,
                'is_pos_enabled' => true,
                'is_bakery_item' => true,
            ],
            [
                'name' => 'Butter Croissant',
                'sku' => 'BAK-CROISSANT-01',
                'barcode' => '200000000002',
                'category_id' => $catPastries?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Flaky, buttery French style pastry.',
                'cost_price' => 0.80,
                'sale_price' => 2.50,
                'mrp_price' => 2.50,
                'stock_qty' => 40.000,
                'alert_qty' => 10.000,
                'reorder_qty' => 20.000,
                'is_pos_enabled' => true,
                'is_bakery_item' => true,
            ],
            [
                'name' => 'Chocolate Fudge Cake (Slice)',
                'sku' => 'BAK-CAKE-CHOC-SL',
                'barcode' => '200000000003',
                'category_id' => $catCakes?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Rich double chocolate cake slice with fudge frosting.',
                'cost_price' => 1.20,
                'sale_price' => 3.80,
                'mrp_price' => 4.00,
                'stock_qty' => 16.000,
                'alert_qty' => 4.000,
                'reorder_qty' => 8.000,
                'is_pos_enabled' => true,
                'is_bakery_item' => true,
            ],
            [
                'name' => 'Chocolate Fudge Whole Cake',
                'sku' => 'BAK-CAKE-CHOC-WH',
                'barcode' => '200000000004',
                'category_id' => $catCakes?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'Rich double chocolate whole cake (8 inches).',
                'cost_price' => 8.00,
                'sale_price' => 28.00,
                'mrp_price' => 30.00,
                'stock_qty' => 4.000,
                'alert_qty' => 1.000,
                'reorder_qty' => 2.000,
                'is_pos_enabled' => true,
                'is_bakery_item' => true,
            ],
            [
                'name' => 'Cake Packaging Box 8"',
                'sku' => 'PKG-BOX-CAKE-8',
                'barcode' => '300000000001',
                'category_id' => $catPackaging?->id,
                'brand_id' => $brandLocal?->id,
                'unit_id' => $unitPcs?->id,
                'tax_id' => $taxDefault?->id,
                'description' => 'White corrugated cardboard box for 8-inch cakes.',
                'cost_price' => 0.40,
                'sale_price' => 0.80,
                'mrp_price' => 0.80,
                'stock_qty' => 200.000,
                'alert_qty' => 30.000,
                'reorder_qty' => 100.000,
                'is_pos_enabled' => false,
                'is_bakery_item' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['sku' => $product['sku']], $product);
        }
    }
}

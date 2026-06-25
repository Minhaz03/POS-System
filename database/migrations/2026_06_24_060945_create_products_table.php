<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique()->nullable();         // auto-generated or manual
            $table->string('barcode')->nullable()->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // Pricing
            $table->decimal('cost_price', 12, 2)->default(0);   // purchase cost
            $table->decimal('sale_price', 12, 2)->default(0);   // selling price
            $table->decimal('mrp_price', 12, 2)->default(0);    // MRP / printed price

            // Stock
            $table->decimal('stock_qty', 12, 3)->default(0);
            $table->decimal('alert_qty', 12, 3)->default(5);    // low-stock threshold
            $table->decimal('reorder_qty', 12, 3)->default(0);  // auto-reorder amount

            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_pos_enabled')->default(true);   // show on POS screen
            $table->boolean('is_bakery_item')->default(false);  // made from recipe

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete();
            $table->foreign('tax_id')->references('id')->on('taxes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

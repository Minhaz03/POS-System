<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product_id')->nullable();  // linked bakery product
            $table->text('description')->nullable();
            $table->string('category')->nullable();                // e.g. Bread, Cake, Pastry
            $table->string('prep_time')->nullable();               // e.g. "2 hours 30 mins"
            $table->string('bake_time')->nullable();               // e.g. "45 mins"
            $table->integer('yield_qty')->default(1);              // how many units produced
            $table->string('yield_unit')->nullable();              // e.g. "loaves", "pieces"
            $table->text('instructions')->nullable();              // step-by-step method
            $table->text('notes')->nullable();                     // baker's notes
            $table->decimal('estimated_cost', 12, 2)->default(0); // auto-calculated ingredient cost
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

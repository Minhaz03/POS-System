<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->text('details');
            $table->date('delivery_date');
            $table->decimal('total_price', 10, 2);
            $table->decimal('advance_payment', 10, 2)->default(0);
            $table->string('status')->default('Pending Review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};

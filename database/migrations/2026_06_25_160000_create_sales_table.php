<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();             // e.g. INV-2026-0001
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('sale_date');

            // Financials
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('amount_tendered', 12, 2)->default(0);  // cash given
            $table->decimal('change_amount', 12, 2)->default(0);    // change returned

            // Payment
            $table->enum('payment_method', ['cash', 'card', 'mobile_pay', 'credit'])->default('cash');
            $table->enum('status', ['completed', 'refunded', 'voided'])->default('completed');

            // Meta
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

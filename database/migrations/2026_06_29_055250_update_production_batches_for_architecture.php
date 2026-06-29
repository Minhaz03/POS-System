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
        Schema::table('production_batches', function (Blueprint $table) {
            $table->date('manufacturing_date')->nullable()->after('completed_at');
            $table->date('expiry_date')->nullable()->after('manufacturing_date');
            $table->decimal('wastage_qty', 12, 3)->default(0)->after('qty');
            $table->text('wastage_notes')->nullable()->after('wastage_qty');
        });
    }

    public function down(): void
    {
        Schema::table('production_batches', function (Blueprint $table) {
            $table->dropColumn(['manufacturing_date', 'expiry_date', 'wastage_qty', 'wastage_notes']);
        });
    }
};

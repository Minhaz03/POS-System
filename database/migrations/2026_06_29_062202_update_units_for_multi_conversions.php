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
        Schema::table('units', function (Blueprint $table) {
            $table->unsignedBigInteger('base_unit_id')->nullable()->after('short_name');
            $table->enum('operator', ['*', '/'])->default('*')->after('base_unit_id');
            $table->decimal('conversion_rate', 12, 4)->default(1)->after('operator');
            
            $table->foreign('base_unit_id')->references('id')->on('units')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['base_unit_id']);
            $table->dropColumn(['base_unit_id', 'operator', 'conversion_rate']);
        });
    }
};

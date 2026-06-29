<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration is a no-op since production_batches already uses scheduled_at.
     */
    public function up(): void
    {
        // The production_batches table already uses scheduled_at (not production_date).
        // This migration file is kept to maintain migration history integrity.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to reverse.
    }
};

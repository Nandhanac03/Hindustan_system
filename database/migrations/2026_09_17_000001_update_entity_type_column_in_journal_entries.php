<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE hindustansystem_journal_entries MODIFY COLUMN entity_type VARCHAR(50) NULL");
        } catch (\Exception $e) {
            // Fallback for different table prefix or connection
            try {
                DB::statement("ALTER TABLE journal_entries MODIFY COLUMN entity_type VARCHAR(50) NULL");
            } catch (\Exception $e2) {
                // Ignore if already modified or fails
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};

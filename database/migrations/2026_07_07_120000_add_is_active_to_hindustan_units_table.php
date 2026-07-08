<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('hindustan_units')) {
            if (!Schema::hasColumn('hindustan_units', 'is_active')) {
                Schema::table('hindustan_units', function (Blueprint $table) {
                    $table->boolean('is_active')->default(true)->after('status');
                });
            }

            try {
                $tableName = DB::getTablePrefix() . 'hindustan_units';
                DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `status` ENUM('available', 'booked', 'sold', 'blocked', 'hold', 'reserved') DEFAULT 'available'");
            } catch (\Exception $e) {
                // Ignore if modifying enum fails on certain DB engines
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('hindustan_units') && Schema::hasColumn('hindustan_units', 'is_active')) {
            Schema::table('hindustan_units', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};

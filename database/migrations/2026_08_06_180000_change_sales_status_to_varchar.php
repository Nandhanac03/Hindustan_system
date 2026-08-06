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
        $prefix = DB::getTablePrefix();
        $tableName = $prefix . 'sales';

        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `status` VARCHAR(30) NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $prefix = DB::getTablePrefix();
        $tableName = $prefix . 'sales';

        DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `status` ENUM('active', 'cancelled', 'returned', 'exchanged', 'resale') NOT NULL DEFAULT 'active'");
    }
};

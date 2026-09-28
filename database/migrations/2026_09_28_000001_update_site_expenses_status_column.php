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
        if (Schema::hasTable('site_expenses')) {
            try {
                Schema::table('site_expenses', function (Blueprint $table) {
                    $table->string('status', 30)->default('Pending')->change();
                });
            } catch (\Throwable $e) {
                $tableName = DB::getTablePrefix() . 'site_expenses';
                DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `status` VARCHAR(30) NOT NULL DEFAULT 'Pending'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('site_expenses')) {
            try {
                Schema::table('site_expenses', function (Blueprint $table) {
                    $table->enum('status', ['Draft', 'Approved', 'Rejected'])->default('Approved')->change();
                });
            } catch (\Throwable $e) {
                $tableName = DB::getTablePrefix() . 'site_expenses';
                DB::statement("ALTER TABLE `{$tableName}` MODIFY COLUMN `status` ENUM('Draft', 'Approved', 'Rejected') NOT NULL DEFAULT 'Approved'");
            }
        }
    }
};

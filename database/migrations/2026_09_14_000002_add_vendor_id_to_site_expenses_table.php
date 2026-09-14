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
        if (Schema::hasTable('site_expenses')) {
            Schema::table('site_expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('site_expenses', 'vendor_id')) {
                    $table->foreignId('vendor_id')->nullable()->after('payee_id')->constrained('vendors')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('site_expenses')) {
            Schema::table('site_expenses', function (Blueprint $table) {
                if (Schema::hasColumn('site_expenses', 'vendor_id')) {
                    $table->dropForeign(['vendor_id']);
                    $table->dropColumn('vendor_id');
                }
            });
        }
    }
};

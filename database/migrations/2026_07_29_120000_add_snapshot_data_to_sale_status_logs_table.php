<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sale_status_logs')) {
            if (!Schema::hasColumn('sale_status_logs', 'snapshot_data')) {
                Schema::table('sale_status_logs', function (Blueprint $table) {
                    $table->json('snapshot_data')->nullable()->after('reason');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sale_status_logs')) {
            if (Schema::hasColumn('sale_status_logs', 'snapshot_data')) {
                Schema::table('sale_status_logs', function (Blueprint $table) {
                    $table->dropColumn('snapshot_data');
                });
            }
        }
    }
};

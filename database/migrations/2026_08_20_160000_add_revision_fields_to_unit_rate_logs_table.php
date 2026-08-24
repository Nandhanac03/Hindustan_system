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
        Schema::table('unit_rate_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('unit_rate_logs', 'revision_type')) {
                $table->string('revision_type', 50)->nullable()->after('rate');
            }
            if (!Schema::hasColumn('unit_rate_logs', 'change_details')) {
                $table->string('change_details', 255)->nullable()->after('revision_type');
            }
            if (!Schema::hasColumn('unit_rate_logs', 'amount_change')) {
                $table->decimal('amount_change', 15, 2)->nullable()->after('change_details');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_rate_logs', function (Blueprint $table) {
            $table->dropColumn(['revision_type', 'change_details', 'amount_change']);
        });
    }
};

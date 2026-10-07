<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('partner_contributions') && !Schema::hasColumn('partner_contributions', 'is_historical')) {
            Schema::table('partner_contributions', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('partner_contributions', 'is_historical')) {
            Schema::table('partner_contributions', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }
    }
};

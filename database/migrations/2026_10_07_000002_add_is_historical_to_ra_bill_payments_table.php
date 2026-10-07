<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ra_bill_payments') && !Schema::hasColumn('ra_bill_payments', 'is_historical')) {
            Schema::table('ra_bill_payments', function (Blueprint $table) {
                $table->boolean('is_historical')->default(false)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ra_bill_payments', 'is_historical')) {
            Schema::table('ra_bill_payments', function (Blueprint $table) {
                $table->dropColumn('is_historical');
            });
        }
    }
};

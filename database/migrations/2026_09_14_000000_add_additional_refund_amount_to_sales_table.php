<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'additional_refund_amount')) {
                $table->decimal('additional_refund_amount', 15, 2)->default(0.00)->after('cancellation_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'additional_refund_amount')) {
                $table->dropColumn('additional_refund_amount');
            }
        });
    }
};

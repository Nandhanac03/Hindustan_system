<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('cancellation_fee', 15, 2)->default(0.00)->after('cancelled_at');
            $table->decimal('refund_amount', 15, 2)->default(0.00)->after('cancellation_fee');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['cancellation_fee', 'refund_amount']);
        });
    }
};

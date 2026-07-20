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
        Schema::table('customer_installments', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_installments', 'paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0.00)->after('amount');
            }
            if (!Schema::hasColumn('customer_installments', 'rescheduled_from_id')) {
                $table->unsignedBigInteger('rescheduled_from_id')->nullable()->after('schedule_type');
                $table->foreign('rescheduled_from_id', 'cust_inst_rescheduled_fk')
                      ->references('id')
                      ->on('customer_installments')
                      ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_installments', function (Blueprint $table) {
            $table->dropForeign(['rescheduled_from_id']);
            $table->dropColumn(['paid_amount', 'rescheduled_from_id']);
        });
    }
};

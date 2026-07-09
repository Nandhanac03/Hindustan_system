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
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('initial_payment', 15, 2)->default(0.00)->after('broker_involved'); 
            $table->enum('payment_plan', ['lump_sum', 'emi'])->default('lump_sum')->after('initial_payment');
            $table->enum('payment_mode', ['cash','cheque','bank_transfer','upi','demand_draft'])->nullable()->after('initial_payment');
            $table->string('reference_no', 100)->nullable()->after('payment_mode');
            $table->string('bank_name', 100)->nullable()->after('reference_no');
            $table->text('remarks')->nullable()->after('reference_no');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('initial_payment');
            $table->dropColumn('payment_plan');
            $table->dropColumn('payment_mode');
            $table->dropColumn('reference_no');
            $table->dropColumn('bank_name');
            $table->dropColumn('remarks');
        });
    }
};

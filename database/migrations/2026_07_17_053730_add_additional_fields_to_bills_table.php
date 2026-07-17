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
        Schema::table('bills', function (Blueprint $table) {
            $table->string('bill_type')->nullable()->after('bill_number');
            $table->string('payment_terms')->nullable()->after('bill_type');
            $table->string('place_of_supply')->nullable()->after('payment_terms');
            $table->string('expense_head')->nullable()->after('place_of_supply');
            $table->string('bill_file')->nullable()->after('expense_head');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['bill_type', 'payment_terms', 'place_of_supply', 'expense_head', 'bill_file']);
        });
    }
};

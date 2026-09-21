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
        Schema::table('petty_cash_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('petty_cash_transactions', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('petty_cash_box_id');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('petty_cash_transactions', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
        });
    }
};

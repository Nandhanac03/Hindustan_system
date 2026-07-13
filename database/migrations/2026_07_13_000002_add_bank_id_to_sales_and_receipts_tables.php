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
            if (Schema::hasColumn('sales', 'bank_name')) {
                $table->dropColumn('bank_name');
            }
            if (!Schema::hasColumn('sales', 'bank_id')) {
                $table->foreignId('bank_id')->nullable()->after('reference_no')->constrained('banks')->nullOnDelete();
            }
        });

        Schema::table('receipts', function (Blueprint $table) {
            if (Schema::hasColumn('receipts', 'bank_name')) {
                $table->dropColumn('bank_name');
            }
            if (!Schema::hasColumn('receipts', 'bank_id')) {
                $table->foreignId('bank_id')->nullable()->after('reference_no')->constrained('banks')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            if (Schema::hasColumn('receipts', 'bank_id')) {
                $table->dropForeign(['bank_id']);
                $table->dropColumn('bank_id');
            }
            $table->string('bank_name')->nullable()->after('reference_no');
        });

        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'bank_id')) {
                $table->dropForeign(['bank_id']);
                $table->dropColumn('bank_id');
            }
            $table->string('bank_name', 100)->nullable()->after('reference_no');
        });
    }
};

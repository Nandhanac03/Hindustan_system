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
        Schema::table('journal_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('journal_vouchers', 'status')) {
                $table->string('status', 20)->default('Posted')->after('narration');
            }
            if (!Schema::hasColumn('journal_vouchers', 'reference_no')) {
                $table->string('reference_no', 100)->nullable()->after('reference_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('journal_vouchers', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('journal_vouchers', 'reference_no')) {
                $table->dropColumn('reference_no');
            }
        });
    }
};

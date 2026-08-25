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
            if (!Schema::hasColumn('petty_cash_transactions', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('narration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petty_cash_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('petty_cash_transactions', 'attachment_path')) {
                $table->dropColumn('attachment_path');
            }
        });
    }
};

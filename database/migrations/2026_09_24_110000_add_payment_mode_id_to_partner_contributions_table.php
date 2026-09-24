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
        if (Schema::hasTable('partner_contributions') && !Schema::hasColumn('partner_contributions', 'payment_mode_id')) {
            Schema::table('partner_contributions', function (Blueprint $table) {
                $table->unsignedBigInteger('payment_mode_id')->nullable()->after('amount');
                $table->foreign('payment_mode_id', 'fk_prtc_payment_mode')->references('id')->on('payment_modes')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('partner_contributions') && Schema::hasColumn('partner_contributions', 'payment_mode_id')) {
            Schema::table('partner_contributions', function (Blueprint $table) {
                $table->dropForeign('fk_prtc_payment_mode');
                $table->dropColumn('payment_mode_id');
            });
        }
    }
};

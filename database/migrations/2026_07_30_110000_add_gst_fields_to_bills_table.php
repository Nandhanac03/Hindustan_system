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
            if (!Schema::hasColumn('bills', 'gst_rate')) {
                $table->decimal('gst_rate', 5, 2)->default(0.00)->after('bill_amount');
            }
            if (!Schema::hasColumn('bills', 'gst_amount')) {
                $table->decimal('gst_amount', 15, 2)->default(0.00)->after('gst_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['gst_rate', 'gst_amount']);
        });
    }
};

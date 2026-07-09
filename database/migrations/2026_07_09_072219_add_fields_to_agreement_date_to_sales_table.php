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
            if (!Schema::hasColumn('sales', 'agreement_date')) {
                $table->date('agreement_date')->nullable()->after('broker_id');
            }
            if (!Schema::hasColumn('sales', 'registration_date')) {
                $table->date('registration_date')->nullable()->after('agreement_date');
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['agreement_date', 'registration_date']);
        });
    }
};

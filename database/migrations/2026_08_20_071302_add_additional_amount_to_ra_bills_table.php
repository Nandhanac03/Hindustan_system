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
        Schema::table('ra_bills', function (Blueprint $table) {
            $table->decimal('additional_amount', 15, 2)->default(0)->after('gross_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ra_bills', function (Blueprint $table) {
            $table->dropColumn('additional_amount');
        });
    }
};

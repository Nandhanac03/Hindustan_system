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
            if (!Schema::hasColumn('ra_bills', 'unit_id')) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('project_id');
                $table->string('unit_name')->nullable()->after('unit_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ra_bills', function (Blueprint $table) {
            if (Schema::hasColumn('ra_bills', 'unit_id')) {
                $table->dropColumn(['unit_id', 'unit_name']);
            }
        });
    }
};

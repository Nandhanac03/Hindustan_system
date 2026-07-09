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
        if (Schema::hasTable('bookings') && Schema::hasTable('hindustan_units')) {
            Schema::table('bookings', function (Blueprint $table) {
                try {
                    $table->dropForeign(['unit_id']);
                } catch (\Exception $e) {
                    // Ignore if constraint name differs or already dropped
                }
                $table->foreign('unit_id')->references('id')->on('hindustan_units')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('unit_rate_logs') && Schema::hasTable('hindustan_units')) {
            Schema::table('unit_rate_logs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['unit_id']);
                } catch (\Exception $e) {
                    // Ignore if constraint name differs or already dropped
                }
                $table->foreign('unit_id')->references('id')->on('hindustan_units')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('unit_status_logs') && Schema::hasTable('hindustan_units')) {
            Schema::table('unit_status_logs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['unit_id']);
                } catch (\Exception $e) {
                    // Ignore if constraint name differs or already dropped
                }
                $table->foreign('unit_id')->references('id')->on('hindustan_units')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('unit_status_logs')) {
            Schema::table('unit_status_logs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['unit_id']);
                } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('unit_rate_logs')) {
            Schema::table('unit_rate_logs', function (Blueprint $table) {
                try {
                    $table->dropForeign(['unit_id']);
                } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                try {
                    $table->dropForeign(['unit_id']);
                } catch (\Exception $e) {}
            });
        }
    }
};

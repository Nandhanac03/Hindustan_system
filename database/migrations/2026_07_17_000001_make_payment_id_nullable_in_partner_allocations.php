<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_allocations', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['payment_id']);
        });

        Schema::table('partner_allocations', function (Blueprint $table) {
            // Alter the column to be nullable
            $table->unsignedBigInteger('payment_id')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('payment_id')->references('id')->on('payments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('partner_allocations', function (Blueprint $table) {
            $table->dropForeign(['payment_id']);
        });

        Schema::table('partner_allocations', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->nullable(false)->change();
            $table->foreign('payment_id')->references('id')->on('payments')->cascadeOnDelete();
        });
    }
};

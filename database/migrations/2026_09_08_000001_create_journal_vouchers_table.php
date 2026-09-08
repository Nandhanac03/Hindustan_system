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
        Schema::create('journal_vouchers', function (Blueprint $table) {
            $table->id();

            // Example: JV-SB-2026-0001
            $table->string('voucher_no', 50)->unique();

            // FK -> voucher_types.id
            $table->unsignedBigInteger('voucher_type_id')->nullable();

            $table->date('voucher_date');

            // Related record ID (Can refer to Sale, Bill, Payment, etc.)
            $table->unsignedBigInteger('reference_id')->nullable();

            // Description / Remarks
            $table->text('narration')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Foreign key
            $table->foreign('voucher_type_id')
                ->references('id')
                ->on('voucher_types')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_vouchers');
    }
};

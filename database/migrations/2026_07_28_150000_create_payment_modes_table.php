<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_modes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_id')->default(1)->index();
            $table->string('name', 100);
            $table->string('code', 50);
            $table->text('description')->nullable();
            $table->boolean('requires_reference')->default(false);
            $table->boolean('requires_bank')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['system_id', 'code']);
        });

        // Seed default Payment Modes
        $defaults = [
            [
                'system_id' => 1,
                'name' => 'Cash',
                'code' => 'CASH',
                'description' => 'Physical cash payment intake or payout.',
                'requires_reference' => false,
                'requires_bank' => false,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'name' => 'Cheque',
                'code' => 'CHEQUE',
                'description' => 'Bank cheque payment requiring cheque number & issuing bank details.',
                'requires_reference' => true,
                'requires_bank' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'name' => 'Bank Transfer (NEFT / RTGS / IMPS)',
                'code' => 'BANK_TRANSFER',
                'description' => 'Direct wire transfer via corporate bank account with UTR reference.',
                'requires_reference' => true,
                'requires_bank' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'name' => 'UPI / Online Payment',
                'code' => 'UPI',
                'description' => 'Digital payment via Google Pay, PhonePe, Paytm, or UPI QR code.',
                'requires_reference' => true,
                'requires_bank' => false,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'name' => 'Credit / Debit Card',
                'code' => 'CARD',
                'description' => 'POS swipe machine card transaction with Auth / Ref ID.',
                'requires_reference' => true,
                'requires_bank' => false,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'system_id' => 1,
                'name' => 'Demand Draft (DD)',
                'code' => 'DD',
                'description' => 'Bank demand draft requiring DD number and bank name.',
                'requires_reference' => true,
                'requires_bank' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('payment_modes')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_modes');
    }
};

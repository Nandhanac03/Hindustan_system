<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds cheque realization status tracking to the receipts table.
     * Bank balance is only updated when status moves to 'realized'.
     */
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            if (!Schema::hasColumn('receipts', 'company_bank_account_id')) {
                $table->foreignId('company_bank_account_id')->nullable()->after('bank_id')->constrained('company_bank_accounts')->nullOnDelete();
            }
        });

        if (!Schema::hasColumn('receipts', 'realization_status')) {
            Schema::table('receipts', function (Blueprint $table) {
                // Status lifecycle: pending → cheque_in_hand → deposited → realized / bounced / cancelled
                $table->enum('realization_status', [
                    'pending',
                    'cheque_in_hand',
                    'deposited',
                    'realized',
                    'bounced',
                    'cancelled',
                ])->default('pending')->after('payment_mode');

                // The date written on the cheque instrument
                $table->date('cheque_date')->nullable()->after('realization_status');

                // Bank the cheque is drawn on (e.g., "HDFC Bank")
                $table->string('drawee_bank', 100)->nullable()->after('cheque_date');

                // Timestamp when bank confirmed clearance
                $table->timestamp('realized_at')->nullable()->after('drawee_bank');

                // Who marked it realized
                $table->unsignedBigInteger('realized_by')->nullable()->after('realized_at');
                $table->foreign('realized_by')->references('id')->on('users')->nullOnDelete();
            });
        }

        // Back-fill: any existing receipts with payment_mode CHEQUE or DD
        // that were already credited to a bank account are set to 'realized'
        // (they already updated the balance, so this keeps data consistent).
        DB::table('receipts')
            ->whereIn('payment_mode', ['CHEQUE', 'Cheque', 'DD', 'Demand Draft (DD)'])
            ->whereNotNull('company_bank_account_id')
            ->update([
                'realization_status' => 'realized',
                'realized_at' => DB::raw('updated_at'),
            ]);

        // Cash / NEFT / UPI / CARD that are already allocated — mark realized
        DB::table('receipts')
            ->whereNotIn('payment_mode', ['CHEQUE', 'Cheque', 'DD', 'Demand Draft (DD)'])
            ->where('realization_status', 'pending')
            ->update([
                'realization_status' => 'realized',
                'realized_at' => DB::raw('updated_at'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            if (Schema::hasColumn('receipts', 'company_bank_account_id')) {
                $table->dropForeign(['company_bank_account_id']);
                $table->dropColumn('company_bank_account_id');
            }
            if (Schema::hasColumn('receipts', 'realized_by')) {
                $table->dropForeign(['realized_by']);
            }
            
            $dropCols = [];
            foreach (['realization_status', 'cheque_date', 'drawee_bank', 'realized_at', 'realized_by'] as $col) {
                if (Schema::hasColumn('receipts', $col)) {
                    $dropCols[] = $col;
                }
            }
            if (!empty($dropCols)) {
                $table->dropColumn($dropCols);
            }
        });
    }
};

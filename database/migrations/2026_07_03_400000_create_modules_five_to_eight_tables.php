<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop any partially created remnants from failed run
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('partner_allocations');
        Schema::dropIfExists('partner_shares');
        Schema::dropIfExists('commission_entries');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('brokers');
        Schema::dropIfExists('emi_schedules');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('replenishment_requests');
        Schema::dropIfExists('petty_cash_entries');
        Schema::dropIfExists('petty_cash_accounts');
        Schema::dropIfExists('bill_payments');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('payees');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('voucher_lines');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('accounts');
        Schema::enableForeignKeyConstraints();

        // 1. Create accounts table
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('type', 20); // Asset, Liability, Income, Expense, Equity
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['system_id', 'code']);
        });

        // 2. Create vouchers table
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('voucher_number');
            $table->string('type', 20); // Receipt, Payment, Contra, Journal, Sales, Purchase
            $table->date('date');
            $table->text('narration')->nullable();
            $table->string('reference_no')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('edited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('Draft'); // Draft, Posted, Cancelled
            $table->foreignId('reversal_of_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();

            $table->unique(['system_id', 'voucher_number']);
        });

        // 3. Create voucher_lines table
        Schema::create('voucher_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->string('line_narration')->nullable();
            $table->timestamps();
        });

        // 4. Create ledger_entries table
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->foreignId('voucher_line_id')->constrained('voucher_lines')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->decimal('running_balance', 15, 2)->default(0.00);
            $table->timestamps();
        });

        // 5. Create payees table
        Schema::create('payees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('type', 20); // Supplier, Contractor, Partner
            $table->string('name');
            $table->foreignId('linked_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->timestamps();
        });

        // 6. Create bills table
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->foreignId('payee_id')->constrained('payees')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('bill_number');
            $table->decimal('bill_amount', 15, 2);
            $table->decimal('final_amount', 15, 2);
            $table->string('status', 30)->default('pending_approval'); // pending_approval, approved_unpaid, partially_paid, paid
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['system_id', 'bill_number']);
        });

        // 7. Create bill_payments table
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->unsignedBigInteger('payee_id');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('bill_id', 'bp_bill_fk')->references('id')->on('bills')->cascadeOnDelete();
            $table->foreign('payee_id', 'bp_payee_fk')->references('id')->on('payees')->cascadeOnDelete();
        });

        // 8. Create petty_cash_accounts table
        Schema::create('petty_cash_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->decimal('float_limit', 15, 2);
            $table->decimal('current_balance', 15, 2)->default(0.00);
            $table->unsignedBigInteger('ledger_account_id');
            $table->timestamps();

            $table->unique(['system_id', 'project_id']);
            $table->foreign('ledger_account_id', 'pca_ledger_fk')->references('id')->on('accounts')->cascadeOnDelete();
        });

        // 9. Create petty_cash_entries table
        Schema::create('petty_cash_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('petty_cash_account_id');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->string('receipt_url')->nullable();
            $table->date('date');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();

            $table->foreign('petty_cash_account_id', 'pce_pca_fk')->references('id')->on('petty_cash_accounts')->cascadeOnDelete();
        });

        // 10. Create replenishment_requests table
        Schema::create('replenishment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('petty_cash_account_id');
            $table->decimal('amount', 15, 2);
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();

            $table->foreign('petty_cash_account_id', 'rr_pca_fk')->references('id')->on('petty_cash_accounts')->cascadeOnDelete();
        });

        // 11. Create loans table
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('lender_name');
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('tenure_months');
            $table->date('start_date');
            $table->string('schedule_type', 20); // reducing_balance, flat
            $table->decimal('outstanding_balance', 15, 2);
            $table->unsignedBigInteger('ledger_account_id');
            $table->unsignedBigInteger('interest_account_id');
            $table->timestamps();

            $table->foreign('ledger_account_id', 'l_ledger_fk')->references('id')->on('accounts')->cascadeOnDelete();
            $table->foreign('interest_account_id', 'l_interest_fk')->references('id')->on('accounts')->cascadeOnDelete();
        });

        // 12. Create emi_schedules table
        Schema::create('emi_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('loan_id');
            $table->integer('installment_no');
            $table->date('due_date');
            $table->decimal('emi_amount', 15, 2);
            $table->decimal('principal_component', 15, 2);
            $table->decimal('interest_component', 15, 2);
            $table->string('status', 20)->default('Due'); // Due, Paid, Overdue
            $table->timestamps();

            $table->foreign('loan_id', 'es_loan_fk')->references('id')->on('loans')->cascadeOnDelete();
        });

        // 13. Create brokers table
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('default_commission_pct', 5, 2);
            $table->foreignId('linked_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->timestamps();
        });

        // 14. Create deals table
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->foreignId('broker_id')->constrained('brokers')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->decimal('sale_value', 15, 2);
            $table->decimal('commission_pct_override', 5, 2)->nullable();
            $table->string('trigger_condition', 30)->default('full_collection'); // full_collection, 50_percent_collected
            $table->timestamps();
        });

        // 15. Create commission_entries table
        Schema::create('commission_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('deal_id');
            $table->decimal('amount', 15, 2);
            $table->string('status', 20)->default('Accrued'); // Accrued, Payable, Paid
            $table->timestamp('triggered_at')->nullable();
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();

            $table->foreign('deal_id', 'ce_deal_fk')->references('id')->on('deals')->cascadeOnDelete();
        });

        // 16. Create partner_shares table
        Schema::create('partner_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('partner_id');
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->decimal('share_pct', 5, 2);
            $table->timestamps();

            $table->unique(['system_id', 'project_id', 'partner_id'], 'ps_sys_proj_part_unique');
            $table->foreign('partner_id', 'ps_partner_fk')->references('id')->on('payees')->cascadeOnDelete();
        });

        // 17. Create partner_allocations table
        Schema::create('partner_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->unsignedBigInteger('partner_id');
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->decimal('allocated_amount', 15, 2);
            $table->date('date');
            $table->foreignId('voucher_id')->nullable()->constrained('vouchers')->nullOnDelete();
            $table->timestamps();

            $table->foreign('partner_id', 'pa_partner_fk')->references('id')->on('payees')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_allocations');
        Schema::dropIfExists('partner_shares');
        Schema::dropIfExists('commission_entries');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('brokers');
        Schema::dropIfExists('emi_schedules');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('replenishment_requests');
        Schema::dropIfExists('petty_cash_entries');
        Schema::dropIfExists('petty_cash_accounts');
        Schema::dropIfExists('bill_payments');
        Schema::dropIfExists('bills');
        Schema::dropIfExists('payees');
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('voucher_lines');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('accounts');
    }
};

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
        if (!Schema::hasTable('site_expenses')) {
            Schema::create('site_expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->default(1);
                $table->string('voucher_number', 50)->unique();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('floor_id')->nullable()->constrained('floors')->onDelete('set null');
                $table->string('tower_block_tag', 100)->nullable();
                $table->date('voucher_date');

                // Payee Hybrid Model
                $table->enum('payee_type', ['registered', 'one_time'])->default('registered');
                $table->foreignId('payee_id')->nullable()->constrained('payees')->onDelete('set null');
                $table->string('casual_payee_name')->nullable();

                // 4000-Series Expense Category Mapping
                $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
                $table->string('expense_category_code', 20);
                $table->string('expense_category_name', 150);

                // Financial Amounts
                $table->decimal('gross_amount', 15, 2);
                $table->decimal('cgst_amount', 15, 2)->default(0.00);
                $table->decimal('sgst_amount', 15, 2)->default(0.00);
                $table->decimal('igst_amount', 15, 2)->default(0.00);
                $table->decimal('total_gst_amount', 15, 2)->default(0.00);
                $table->decimal('net_amount', 15, 2);

                // Payment Source (Bank/Loan Only)
                $table->enum('payment_source_type', ['bank', 'loan'])->default('bank');
                $table->foreignId('company_bank_account_id')->nullable()->constrained('company_bank_accounts')->onDelete('set null');
                $table->foreignId('loan_id')->nullable()->constrained('loans')->onDelete('set null');
                $table->string('transaction_reference_no', 100);

                // Meta & DMS Attachment
                $table->text('narration')->nullable();
                $table->string('attachment_path')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('status', ['Draft', 'Approved', 'Rejected'])->default('Approved');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_expenses');
    }
};

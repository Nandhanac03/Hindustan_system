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
        Schema::create('loan_disbursals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_id')->index();
            $table->unsignedBigInteger('loan_id')->index();
            $table->string('disbursal_no', 50)->index();
            $table->date('disbursal_date');
            $table->decimal('amount', 15, 2);
            $table->string('reference_no', 191)->nullable();
            $table->string('transaction_no', 191)->nullable();
            $table->string('disbursal_type', 50);
            $table->string('status', 20)->default('DRAFT');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('posted_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('posted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('system_id')->references('id')->on('systems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_disbursals');
    }
};

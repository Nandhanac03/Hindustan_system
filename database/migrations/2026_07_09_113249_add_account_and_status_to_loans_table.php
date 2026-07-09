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
        Schema::table('loans', function (Blueprint $table) {
            $table->string('loan_account_no', 50)->nullable()->after('project_id');
            $table->string('status', 20)->default('Active')->after('interest_account_id');
        });

        Schema::create('loan_prepayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->decimal('prepayment_amount', 15, 2);
            $table->date('prepayment_date');
            $table->string('reschedule_option', 20);
            $table->decimal('previous_outstanding', 15, 2);
            $table->decimal('new_outstanding', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_prepayments');

        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['loan_account_no', 'status']);
        });
    }
};

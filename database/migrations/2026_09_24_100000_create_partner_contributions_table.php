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
        Schema::dropIfExists('partner_contributions');

        Schema::create('partner_contributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('system_id')->nullable()->default(1);
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('company_bank_account_id');
            $table->date('contribution_date');
            $table->decimal('amount', 15, 2);
            $table->string('reference_no')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->default('Posted');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('partner_id', 'fk_prtc_partner')->references('id')->on('payees')->onDelete('cascade');
            $table->foreign('project_id', 'fk_prtc_project')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('company_bank_account_id', 'fk_prtc_bank')->references('id')->on('company_bank_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_contributions');
    }
};

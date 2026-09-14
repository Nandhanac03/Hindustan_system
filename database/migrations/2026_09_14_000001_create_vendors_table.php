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
        if (!Schema::hasTable('vendors')) {
            Schema::create('vendors', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->default(1);
                $table->string('vendor_code', 50)->unique();
                $table->string('name', 191);
                $table->string('contact_person', 191)->nullable();
                $table->string('phone', 50)->nullable();
                $table->string('email', 191)->nullable();
                $table->string('gstin', 15)->nullable();
                $table->string('pan', 20)->nullable();
                $table->text('address')->nullable();
                
                // Banking details
                $table->string('bank_name', 150)->nullable();
                $table->string('account_number', 50)->nullable();
                $table->string('ifsc_code', 20)->nullable();
                $table->string('branch', 100)->nullable();
                
                // Linked ledger account in accounts table
                $table->foreignId('linked_account_id')->nullable()->constrained('accounts')->onDelete('set null');
                
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};

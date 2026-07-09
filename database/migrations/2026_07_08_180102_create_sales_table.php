<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->string('sale_number')->unique();

                // Core relations
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->foreignId('unit_id')->constrained('hindustan_units')->cascadeOnDelete();
                $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->foreignId('broker_id')->nullable()->constrained('brokers')->nullOnDelete();

                // Pricing
                $table->decimal('rate_per_sqft', 15, 2)->nullable();
                $table->decimal('sale_amount', 15, 2);

                // GST handling
                $table->boolean('gst_applicable')->default(false);
                $table->decimal('gst_percentage', 5, 2)->nullable();
                $table->decimal('gst_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2);

                // Sale lifecycle
                $table->date('sale_date');
                $table->enum('status', ['active', 'cancelled', 'returned', 'exchanged', 'resale'])->default('active');

                // Exchange / Resale tracking — self-referencing chain
                $table->foreignId('original_sale_id')->nullable()->constrained('sales')->nullOnDelete();
                $table->boolean('is_resale')->default(false);

                // Cancellation / return details
                $table->string('cancellation_reason')->nullable();
                $table->timestamp('cancelled_at')->nullable();

                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (! Schema::hasTable('sale_status_logs')) {
            Schema::create('sale_status_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
                $table->string('from_status')->nullable();
                $table->string('to_status');
                $table->string('event_type'); // cancellation, return, exchange, resale, status_update
                $table->text('reason')->nullable();
                $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_status_logs');
        Schema::dropIfExists('sales');
    }
};
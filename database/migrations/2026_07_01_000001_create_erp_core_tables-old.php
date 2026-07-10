<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('avatar_url')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('sales_executives')) {
            Schema::create('sales_executives', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('avatar_url')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_number')->unique();
                $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->unsignedBigInteger('project_id');
                $table->unsignedBigInteger('unit_id');
                $table->foreignId('sales_executive_id')->constrained('sales_executives')->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->string('status'); // pending_approval, approved, rejected
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('receipt_number')->unique();
                $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
                $table->decimal('amount', 15, 2);
                $table->string('payment_mode'); // Cash, Bank Transfer, Credit Card, UPI
                $table->string('status');        // completed, pending, failed
                $table->timestamp('payment_date');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('approval_requests')) {
            Schema::create('approval_requests', function (Blueprint $table) {
                $table->id();
                $table->string('type');     // Booking, Discount, Refund, Cancellation
                $table->string('priority'); // low, medium, high, critical
                $table->string('status');   // pending, approved, rejected
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                $table->string('requester_name')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('event_type');
                $table->text('description');
                $table->string('user_name');
                $table->string('user_avatar')->nullable();
                $table->string('color_code');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('user_dashboard_layouts')) {
            Schema::create('user_dashboard_layouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->json('layout_settings');
                $table->timestamps();
            });
        }
    }

  public function down(): void
{
    Schema::disableForeignKeyConstraints();

    Schema::dropIfExists('hindustansystem_partner_allocations');
    Schema::dropIfExists('hindustansystem_payments');
    // ...any other tables in this migration, in any order

    Schema::enableForeignKeyConstraints();
}
};

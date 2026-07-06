<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('image_url')->nullable();
                $table->integer('total_units');
                $table->integer('sold_units')->default(0);
                $table->decimal('revenue', 15, 2)->default(0.00);
                $table->decimal('completion_percentage', 5, 2)->default(0.00);
                $table->string('status');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->string('name');
                $table->string('floor');
                $table->string('status'); // available, booked, reserved, sold, cancelled
                $table->decimal('price', 15, 2);
                $table->timestamps();
            });
        }

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
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
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
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
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
        Schema::dropIfExists('user_dashboard_layouts');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('sales_executives');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('units');
        Schema::dropIfExists('projects');
    }
};

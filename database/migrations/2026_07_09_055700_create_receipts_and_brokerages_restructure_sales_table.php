<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Receipts: every payment against a sale, tracked individually ──
        if (! Schema::hasTable('receipts')) {
            Schema::create('receipts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
                $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->foreignId('unit_id')->constrained('hindustan_units')->cascadeOnDelete();
                $table->date('receipt_date');
                $table->decimal('amount', 15, 2);
                $table->string('payment_mode')->default('cash');
                $table->string('reference_no')->nullable();
                $table->string('bank_name')->nullable();
                $table->text('remarks')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // ── Brokerages: one commission record per sale, with payout tracking ──
        if (! Schema::hasTable('brokerages')) {
            Schema::create('brokerages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
                $table->foreignId('broker_id')->constrained('brokers')->cascadeOnDelete();
                $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
                $table->decimal('commission_percent', 5, 2)->nullable();
                $table->decimal('commission_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // ── Sales table: rename GST terminology, drop now-redundant flat columns ──
        Schema::table('sales', function (Blueprint $table) {
            if (! Schema::hasColumn('sales', 'gst_type')) {
                $table->enum('gst_type', ['none', 'inclusive', 'exclusive'])->default('none')->after('gst_applicable');
            }
        });

        // Migrate existing gst_behavior values into gst_type before dropping the old column
        if (Schema::hasColumn('sales', 'gst_behavior')) {
            DB::table('sales')->where('gst_behavior', 'included')->update(['gst_type' => 'inclusive']);
            DB::table('sales')->where('gst_behavior', 'excluded')->update(['gst_type' => 'exclusive']);
            DB::table('sales')->where('gst_behavior', 'none')->update(['gst_type' => 'none']);
        }

        Schema::table('sales', function (Blueprint $table) {
            $dropIfExists = array_filter([
                'gst_behavior',
                'brokerage_type', 'brokerage_value', 'brokerage_amount', 'brokerage_status',
                'initial_payment_amount', 'payment_mode', 'initial_payment_date',
            ], fn ($col) => Schema::hasColumn('sales', $col));

            if (! empty($dropIfExists)) {
                $table->dropColumn($dropIfExists);
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'gst_type')) {
                $table->dropColumn('gst_type');
            }
            $table->enum('gst_behavior', ['none', 'included', 'excluded'])->default('none');
            $table->enum('brokerage_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('brokerage_value', 15, 2)->nullable();
            $table->decimal('brokerage_amount', 15, 2)->default(0);
            $table->enum('brokerage_status', ['pending', 'paid'])->default('pending');
            $table->decimal('initial_payment_amount', 15, 2)->default(0);
            $table->string('payment_mode')->nullable();
            $table->date('initial_payment_date')->nullable();
        });

        Schema::dropIfExists('brokerages');
        Schema::dropIfExists('receipts');
    }
};
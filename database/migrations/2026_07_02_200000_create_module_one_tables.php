<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create systems table
        if (!Schema::hasTable('systems')) {
            Schema::create('systems', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code', 2)->unique();
                $table->string('country');
                $table->string('currency_code', 3);
                $table->boolean('gst_enabled')->default(false);
                $table->boolean('vat_enabled')->default(false);
                $table->string('timezone');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Extend users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'system_id')) {
                $table->foreignId('system_id')->nullable()->constrained('systems')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'employee_code')) {
                $table->string('employee_code')->unique()->nullable();
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status', 20)->default('active'); // active, inactive, suspended
            }
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false);
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable();
            }
        });

        // 3. Create approval_rules table
        if (!Schema::hasTable('approval_rules')) {
            Schema::create('approval_rules', function (Blueprint $table) {
                $table->id();
                $table->string('module');
                $table->string('min_role');
                $table->decimal('threshold_amount', 15, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Create approvals table
        if (!Schema::hasTable('approvals')) {
            Schema::create('approvals', function (Blueprint $table) {
                $table->id();
                $table->nullableMorphs('approvable');
                $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('status', 20)->default('pending'); // pending, approved, rejected
                $table->text('reason')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        // 5. Recreate activity_logs table to fit the Module 1 specs
        Schema::dropIfExists('activity_logs');
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('system_id')->nullable()->constrained('systems')->nullOnDelete();
            $table->string('action');
            $table->nullableMorphs('subject');
            $table->string('description');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('approval_rules');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['system_id']);
            $table->dropColumn([
                'system_id',
                'phone',
                'employee_code',
                'status',
                'must_change_password',
                'last_login_at',
                'last_login_ip'
            ]);
        });
        
        Schema::dropIfExists('systems');
    }
};

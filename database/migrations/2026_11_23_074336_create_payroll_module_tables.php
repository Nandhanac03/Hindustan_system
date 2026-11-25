<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payroll_financial_periods')) {
            Schema::create('payroll_financial_periods', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->nullable()->index();
                $table->string('name', 150);
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('working_days')->default(26);
                $table->boolean('is_current')->default(false);
                $table->string('status', 30)->default('active'); // active, closed, inactive
                $table->string('notes', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payroll_leave_quotas')) {
            Schema::create('payroll_leave_quotas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->nullable()->index();
                $table->unsignedBigInteger('financial_period_id')->nullable()->index();
                $table->string('leave_type', 100);
                $table->decimal('annual_quota', 6, 1)->default(12);
                $table->decimal('monthly_accrual', 5, 2)->default(1);
                $table->boolean('carry_forward')->default(false);
                $table->decimal('max_carry_forward', 6, 1)->default(0);
                $table->string('description', 255)->nullable();
                $table->string('status', 30)->default('active'); // active, inactive
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payroll_salary_heads')) {
            Schema::create('payroll_salary_heads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->nullable()->index();
                $table->unsignedBigInteger('financial_period_id')->nullable()->index();
                $table->string('name', 150); // e.g. November 2026
                $table->unsignedTinyInteger('month')->default(1);
                $table->unsignedSmallInteger('year')->default(2026);
                $table->date('payment_date')->nullable();
                $table->integer('total_employees')->default(0);
                $table->decimal('total_gross', 14, 2)->default(0);
                $table->decimal('total_deductions', 14, 2)->default(0);
                $table->decimal('total_net', 14, 2)->default(0);
                $table->string('status', 30)->default('Draft'); // Draft, Processed, Paid
                $table->string('notes', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payroll_salaries')) {
            Schema::create('payroll_salaries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->nullable()->index();
                $table->unsignedBigInteger('salary_head_id')->nullable()->index();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('payroll_month', 60)->nullable();
                $table->decimal('working_days', 5, 1)->default(26);
                $table->decimal('present_days', 5, 1)->default(26);
                $table->decimal('leave_days', 5, 1)->default(0);
                $table->decimal('overtime_hours', 6, 2)->default(0);
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('hra', 12, 2)->default(0);
                $table->decimal('allowances', 12, 2)->default(0);
                $table->decimal('overtime_pay', 12, 2)->default(0);
                $table->decimal('gross_salary', 12, 2)->default(0);
                $table->decimal('deductions', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->date('payment_date')->nullable();
                $table->string('payment_mode', 50)->default('Bank Transfer');
                $table->string('status', 30)->default('Pending'); // Pending, Processed, Paid
                $table->string('remarks', 255)->nullable();
                $table->timestamps();
            });
        }

        // Register Payroll Module & Sub-Modules in erp_modules & erp_sub_modules
        $now = now();
        $module = DB::table('erp_modules')->where('code', 'payroll')->first();
        if (!$module) {
            $maxOrder = (int) DB::table('erp_modules')->max('order');
            $moduleId = DB::table('erp_modules')->insertGetId([
                'name' => 'Payroll',
                'code' => 'payroll',
                'section' => 'Human Capital',
                'icon' => 'banknotes',
                'order' => $maxOrder + 1,
                'status' => 'active',
                'description' => 'Financial periods, leave quotas, salary heads, monthly salary processing, and salary reports',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $moduleId = $module->id;
        }

        $subModules = [
            [
                'name' => 'Financial Period',
                'code' => 'payroll.financial_period',
                'route_name' => 'payroll.financial-period.index',
                'actions' => ['view', 'create', 'edit', 'delete'],
                'description' => 'Configure payroll financial periods and working days',
                'order' => 1,
            ],
            [
                'name' => 'Leave Quota',
                'code' => 'payroll.leave_quota',
                'route_name' => 'payroll.leave-quota.index',
                'actions' => ['view', 'create', 'edit', 'delete'],
                'description' => 'Manage annual leave quotas and monthly accruals',
                'order' => 2,
            ],
            [
                'name' => 'Salary Head',
                'code' => 'payroll.salary_head',
                'route_name' => 'payroll.salary-head.index',
                'actions' => ['view', 'create', 'edit', 'delete'],
                'description' => 'Monthly payroll batches and salary head summaries',
                'order' => 3,
            ],
            [
                'name' => 'Salary',
                'code' => 'payroll.salary',
                'route_name' => 'payroll.salary.index',
                'actions' => ['view', 'create', 'edit', 'delete', 'excel', 'pdf'],
                'description' => 'Employee salary processing, deductions, and disbursement',
                'order' => 4,
            ],
            [
                'name' => 'Salary Report',
                'code' => 'payroll.salary_report',
                'route_name' => 'payroll.salary-report.index',
                'actions' => ['view', 'excel', 'pdf'],
                'description' => 'Salary report summary by payroll head and employee',
                'order' => 5,
            ],
        ];

        foreach ($subModules as $sm) {
            $existing = DB::table('erp_sub_modules')->where('code', $sm['code'])->first();
            if ($existing) {
                DB::table('erp_sub_modules')->where('id', $existing->id)->update([
                    'erp_module_id' => $moduleId,
                    'name' => $sm['name'],
                    'route_name' => $sm['route_name'],
                    'actions' => json_encode($sm['actions']),
                    'description' => $sm['description'],
                    'order' => $sm['order'],
                    'status' => 'active',
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('erp_sub_modules')->insert([
                    'erp_module_id' => $moduleId,
                    'name' => $sm['name'],
                    'code' => $sm['code'],
                    'route_name' => $sm['route_name'],
                    'actions' => json_encode($sm['actions']),
                    'description' => $sm['description'],
                    'order' => $sm['order'],
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach ($sm['actions'] as $action) {
                $permName = "{$sm['code']}.{$action}";
                if (!DB::table('permissions')->where('name', $permName)->where('guard_name', 'web')->exists()) {
                    DB::table('permissions')->insert([
                        'name' => $permName,
                        'guard_name' => 'web',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_salaries');
        Schema::dropIfExists('payroll_salary_heads');
        Schema::dropIfExists('payroll_leave_quotas');
        Schema::dropIfExists('payroll_financial_periods');
    }
};

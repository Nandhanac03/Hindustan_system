<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (!Schema::hasColumn('employees', 'salary_structure')) {
                    $table->string('salary_structure', 191)->nullable()->after('designation');
                }
                if (!Schema::hasColumn('employees', 'bank_country')) {
                    $table->string('bank_country', 100)->nullable()->default('India')->after('uan_number');
                }
            });
        }

        if (!Schema::hasTable('salary_components')) {
            Schema::create('salary_components', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->nullable()->index();
                $table->string('name', 150);
                $table->enum('type', ['allowance', 'deduction'])->default('allowance');
                $table->string('description', 255)->nullable();
                $table->string('status', 30)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_salary_components')) {
            Schema::create('employee_salary_components', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->unsignedBigInteger('salary_component_id')->nullable();
                $table->index('salary_component_id', 'esc_comp_id_idx');
                $table->string('name', 150);
                $table->string('description', 255)->nullable();
                $table->enum('type', ['allowance', 'deduction'])->default('allowance');
                $table->string('source', 40)->default('Structure'); // Structure, Custom
                $table->string('calculation_type', 40)->default('Fixed Amount'); // Fixed Amount, Percentage
                $table->decimal('configured_value', 12, 2)->default(0);
                $table->decimal('calculated_amount', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_salary_revisions')) {
            Schema::create('employee_salary_revisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('structure_name', 191)->nullable();
                $table->date('effective_from');
                $table->date('effective_to')->nullable();
                $table->string('revision_label', 100)->default('Salary Revision');
                $table->boolean('is_current')->default(true);
                $table->decimal('base_salary', 12, 2)->default(0);
                $table->decimal('gross_salary', 12, 2)->default(0);
                $table->decimal('total_allowances', 12, 2)->default(0);
                $table->decimal('total_deductions', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->string('notes', 255)->nullable();
                $table->json('breakdown')->nullable();
                $table->timestamps();
            });
        }

        $now = now();

        // Seed standard master Allowance & Deduction components if empty and rename 

        // Register Allowance & Deduction Master sub-module under Payroll
        $payrollMod = DB::table('erp_modules')->where('code', 'payroll')->first();
        if ($payrollMod) {
            $subCode = 'payroll.components';
            $existing = DB::table('erp_sub_modules')->where('code', $subCode)->first();
            $actions = ['view', 'create', 'edit', 'delete'];
            if (!$existing) {
                DB::table('erp_sub_modules')->insert([
                    'erp_module_id' => $payrollMod->id,
                    'name' => 'Allowance & Deduction',
                    'code' => $subCode,
                    'route_name' => 'payroll.components.index',
                    'actions' => json_encode($actions),
                    'description' => 'Master table for salary allowances and deductions',
                    'order' => 6,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            foreach ($actions as $act) {
                $permName = "{$subCode}.{$act}";
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
        Schema::dropIfExists('employee_salary_revisions');
        Schema::dropIfExists('employee_salary_components');
        Schema::dropIfExists('salary_components');
    }
};

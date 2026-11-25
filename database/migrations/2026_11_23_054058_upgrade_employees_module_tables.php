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
                if (!Schema::hasColumn('employees', 'first_name')) {
                    $table->string('first_name', 100)->nullable()->after('name');
                }
                if (!Schema::hasColumn('employees', 'last_name')) {
                    $table->string('last_name', 100)->nullable()->after('first_name');
                }
                if (!Schema::hasColumn('employees', 'gender')) {
                    $table->string('gender', 30)->nullable()->after('last_name');
                }
                if (!Schema::hasColumn('employees', 'date_of_birth')) {
                    $table->date('date_of_birth')->nullable()->after('gender');
                }
                if (!Schema::hasColumn('employees', 'nationality')) {
                    $table->string('nationality', 100)->nullable()->after('date_of_birth');
                }
                if (!Schema::hasColumn('employees', 'blood_group')) {
                    $table->string('blood_group', 20)->nullable()->after('nationality');
                }
                if (!Schema::hasColumn('employees', 'personal_email')) {
                    $table->string('personal_email', 191)->nullable()->after('email');
                }
                if (!Schema::hasColumn('employees', 'work_email')) {
                    $table->string('work_email', 191)->nullable()->after('personal_email');
                }
                if (!Schema::hasColumn('employees', 'portal_email')) {
                    $table->string('portal_email', 191)->nullable()->after('work_email');
                }
                if (!Schema::hasColumn('employees', 'alternate_phone')) {
                    $table->string('alternate_phone', 50)->nullable()->after('phone');
                }
                if (!Schema::hasColumn('employees', 'address_line_1')) {
                    $table->string('address_line_1', 255)->nullable()->after('alternate_phone');
                }
                if (!Schema::hasColumn('employees', 'address_line_2')) {
                    $table->string('address_line_2', 255)->nullable()->after('address_line_1');
                }
                if (!Schema::hasColumn('employees', 'country')) {
                    $table->string('country', 100)->nullable()->after('address_line_2');
                }
                if (!Schema::hasColumn('employees', 'state')) {
                    $table->string('state', 100)->nullable()->after('country');
                }
                if (!Schema::hasColumn('employees', 'city')) {
                    $table->string('city', 100)->nullable()->after('state');
                }
                if (!Schema::hasColumn('employees', 'postal_code')) {
                    $table->string('postal_code', 30)->nullable()->after('city');
                }
                if (!Schema::hasColumn('employees', 'branch')) {
                    $table->string('branch', 150)->nullable()->after('department');
                }
                if (!Schema::hasColumn('employees', 'reports_to')) {
                    $table->string('reports_to', 150)->nullable()->after('branch');
                }
                if (!Schema::hasColumn('employees', 'employment_type')) {
                    $table->string('employment_type', 100)->nullable()->default('Full-Time Permanent')->after('reports_to');
                }
                if (!Schema::hasColumn('employees', 'shift')) {
                    $table->string('shift', 100)->nullable()->default('General Day Shift')->after('employment_type');
                }
                if (!Schema::hasColumn('employees', 'confirmation_date')) {
                    $table->date('confirmation_date')->nullable()->after('joining_date');
                }
                if (!Schema::hasColumn('employees', 'probation_end_date')) {
                    $table->date('probation_end_date')->nullable()->after('confirmation_date');
                }
                if (!Schema::hasColumn('employees', 'notice_period')) {
                    $table->string('notice_period', 50)->nullable()->after('probation_end_date');
                }
                if (!Schema::hasColumn('employees', 'work_location')) {
                    $table->string('work_location', 150)->nullable()->after('notice_period');
                }
                if (!Schema::hasColumn('employees', 'photo_path')) {
                    $table->string('photo_path', 255)->nullable()->after('work_location');
                }
                if (!Schema::hasColumn('employees', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('system_id');
                }
                if (!Schema::hasColumn('employees', 'bank_name')) {
                    $table->string('bank_name', 150)->nullable()->after('salary');
                }
                if (!Schema::hasColumn('employees', 'bank_account_number')) {
                    $table->string('bank_account_number', 100)->nullable()->after('bank_name');
                }
                if (!Schema::hasColumn('employees', 'ifsc_code')) {
                    $table->string('ifsc_code', 50)->nullable()->after('bank_account_number');
                }
                if (!Schema::hasColumn('employees', 'pan_number')) {
                    $table->string('pan_number', 50)->nullable()->after('ifsc_code');
                }
                if (!Schema::hasColumn('employees', 'uan_number')) {
                    $table->string('uan_number', 50)->nullable()->after('pan_number');
                }
                if (!Schema::hasColumn('employees', 'basic_salary')) {
                    $table->decimal('basic_salary', 12, 2)->nullable()->default(0)->after('salary');
                }
                if (!Schema::hasColumn('employees', 'hra')) {
                    $table->decimal('hra', 12, 2)->nullable()->default(0)->after('basic_salary');
                }
                if (!Schema::hasColumn('employees', 'allowances')) {
                    $table->decimal('allowances', 12, 2)->nullable()->default(0)->after('hra');
                }
                if (!Schema::hasColumn('employees', 'deductions')) {
                    $table->decimal('deductions', 12, 2)->nullable()->default(0)->after('allowances');
                }
            });
        }

        if (!Schema::hasTable('employee_experiences')) {
            Schema::create('employee_experiences', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('company_name', 191);
                $table->string('job_title', 191);
                $table->date('from_date')->nullable();
                $table->date('to_date')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_attendances')) {
            Schema::create('employee_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->date('attendance_date');
                $table->string('check_in', 20)->nullable();
                $table->string('check_out', 20)->nullable();
                $table->string('status', 30)->default('Present'); // Present, Absent, Half Day, Late, On Leave
                $table->string('notes', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_overtimes')) {
            Schema::create('employee_overtimes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->date('overtime_date');
                $table->decimal('hours', 6, 2)->default(0);
                $table->decimal('rate', 10, 2)->default(0);
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('reason', 255)->nullable();
                $table->string('status', 30)->default('Approved'); // Pending, Approved, Rejected
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_leaves')) {
            Schema::create('employee_leaves', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('leave_type', 60); // Casual Leave, Sick Leave, Earned Leave, Unpaid Leave
                $table->date('from_date');
                $table->date('to_date');
                $table->decimal('days', 5, 1)->default(1);
                $table->string('reason', 255)->nullable();
                $table->string('status', 30)->default('Approved'); // Pending, Approved, Rejected
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_payrolls')) {
            Schema::create('employee_payrolls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('payroll_month', 30); // e.g. November 2026
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('allowances', 12, 2)->default(0);
                $table->decimal('overtime_pay', 12, 2)->default(0);
                $table->decimal('deductions', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->date('payment_date')->nullable();
                $table->string('status', 30)->default('Paid'); // Paid, Pending, Processing
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_documents')) {
            Schema::create('employee_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('title', 191);
                $table->string('document_type', 80)->nullable();
                $table->string('file_path', 255);
                $table->string('notes', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_assets')) {
            Schema::create('employee_assets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('asset_name', 191);
                $table->string('asset_code', 80)->nullable();
                $table->string('category', 80)->nullable();
                $table->date('assigned_date')->nullable();
                $table->date('return_date')->nullable();
                $table->string('status', 30)->default('Assigned'); // Assigned, Returned, Maintenance
                $table->string('notes', 255)->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_emergency_contacts')) {
            Schema::create('employee_emergency_contacts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('name', 150);
                $table->string('relationship', 80);
                $table->string('phone', 50);
                $table->string('alternate_phone', 50)->nullable();
                $table->string('address', 255)->nullable();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }

        // Register dedicated Employees module in erp_modules & erp_sub_modules
        $now = now();
        $module = DB::table('erp_modules')->where('code', 'employees')->first();
        if (!$module) {
            $maxOrder = (int) DB::table('erp_modules')->max('order');
            $moduleId = DB::table('erp_modules')->insertGetId([
                'name' => 'Employees',
                'code' => 'employees',
                'section' => 'Human Capital',
                'icon' => 'users',
                'order' => $maxOrder + 1,
                'status' => 'active',
                'description' => 'Employee directory, HR profiles, attendance, payroll, documents, and assets',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $moduleId = $module->id;
        }

        $subModule = DB::table('erp_sub_modules')->where('code', 'employees.manage')->first();
        if ($subModule) {
            DB::table('erp_sub_modules')->where('id', $subModule->id)->update([
                'erp_module_id' => $moduleId,
                'name' => 'Employees',
                'route_name' => 'employees.index',
                'actions' => json_encode(['view', 'create', 'edit', 'delete', 'excel', 'pdf']),
                'description' => 'Employee directory, HR profiles, attendance, salary, payroll, documents, and assets',
                'order' => 1,
                'status' => 'active',
                'updated_at' => $now,
            ]);
        } else {
            DB::table('erp_sub_modules')->insert([
                'erp_module_id' => $moduleId,
                'name' => 'Employees',
                'code' => 'employees.manage',
                'route_name' => 'employees.index',
                'actions' => json_encode(['view', 'create', 'edit', 'delete', 'excel', 'pdf']),
                'description' => 'Employee directory, HR profiles, attendance, salary, payroll, documents, and assets',
                'order' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach (['view', 'create', 'edit', 'delete', 'excel', 'pdf'] as $action) {
            $permName = "employees.manage.{$action}";
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

    public function down(): void
    {
        Schema::dropIfExists('employee_emergency_contacts');
        Schema::dropIfExists('employee_assets');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_payrolls');
        Schema::dropIfExists('employee_leaves');
        Schema::dropIfExists('employee_overtimes');
        Schema::dropIfExists('employee_attendances');
        Schema::dropIfExists('employee_experiences');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('office_rents')) {
            Schema::create('office_rents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('system_id')->default(1);
                $table->string('office_location', 150);
                $table->string('landlord_name', 150);
                $table->unsignedTinyInteger('rent_month');
                $table->unsignedSmallInteger('rent_year');
                $table->string('rent_period_label', 60);
                $table->decimal('gross_rent_amount', 15, 2);
                $table->decimal('tds_amount', 15, 2)->default(0);
                $table->decimal('net_payable_amount', 15, 2);
                $table->enum('payment_status', ['Paid', 'Unpaid'])->default('Unpaid');
                $table->unsignedBigInteger('company_bank_account_id')->nullable();
                $table->string('payment_reference', 100)->nullable();
                $table->date('voucher_date');
                $table->date('payment_date')->nullable();
                $table->unsignedBigInteger('journal_voucher_id')->nullable();
                $table->unsignedBigInteger('payment_voucher_id')->nullable();
                $table->string('remarks', 255)->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->index('system_id', 'or_sys_idx');
                $table->index(['rent_year', 'rent_month'], 'or_period_idx');
                $table->index('payment_status', 'or_status_idx');
                $table->index('company_bank_account_id', 'or_bank_idx');
            });
        }

        // Register Office Rent in ERP Modules & Permissions
        $now = now();
        $existingMod = DB::table('erp_modules')->where('code', 'office_rent')->first();
        if ($existingMod) {
            $moduleId = $existingMod->id;
            DB::table('erp_modules')->where('id', $moduleId)->update([
                'name' => 'Office Rent',
                'section' => 'Finance & Analytics',
                'icon' => 'building-office-2',
                'description' => 'Office rent booking, TDS deduction, and bank disbursement',
                'order' => 19,
                'status' => 'active',
                'updated_at' => $now,
            ]);
        } else {
            $moduleId = DB::table('erp_modules')->insertGetId([
                'name' => 'Office Rent',
                'code' => 'office_rent',
                'section' => 'Finance & Analytics',
                'icon' => 'building-office-2',
                'description' => 'Office rent booking, TDS deduction, and bank disbursement',
                'order' => 19,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $actions = ['view', 'create', 'edit', 'delete'];
        $existingSub = DB::table('erp_sub_modules')->where('code', 'office_rent.manage')->first();
        if ($existingSub) {
            DB::table('erp_sub_modules')->where('id', $existingSub->id)->update([
                'erp_module_id' => $moduleId,
                'name' => 'Office Rent',
                'route_name' => 'office-rent.index',
                'actions' => json_encode($actions),
                'description' => 'Manage office rent entries, TDS deductions, and landlord payments',
                'order' => 1,
                'status' => 'active',
                'updated_at' => $now,
            ]);
        } else {
            DB::table('erp_sub_modules')->insert([
                'erp_module_id' => $moduleId,
                'name' => 'Office Rent',
                'code' => 'office_rent.manage',
                'route_name' => 'office-rent.index',
                'actions' => json_encode($actions),
                'description' => 'Manage office rent entries, TDS deductions, and landlord payments',
                'order' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($actions as $action) {
            $permName = "office_rent.manage.{$action}";
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
        Schema::dropIfExists('office_rents');
    }
};

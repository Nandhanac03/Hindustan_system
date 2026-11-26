<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('employee_leave_quotas')->truncate();

        Schema::table('employee_leave_quotas', function (Blueprint $table) {
            foreach (['casual_allowed', 'medical_allowed', 'casual_taken', 'medical_taken'] as $col) {
                if (Schema::hasColumn('employee_leave_quotas', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (!Schema::hasColumn('employee_leave_quotas', 'allowed_leave')) {
                $table->decimal('allowed_leave', 5, 1)->default(0)->after('employee_id');
            }
            if (!Schema::hasColumn('employee_leave_quotas', 'taken_leave')) {
                $table->decimal('taken_leave', 5, 1)->default(0)->after('allowed_leave');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee_leave_quotas', function (Blueprint $table) {
            foreach (['allowed_leave', 'taken_leave'] as $col) {
                if (Schema::hasColumn('employee_leave_quotas', $col)) {
                    $table->dropColumn($col);
                }
            }
            $table->decimal('casual_allowed', 5, 1)->default(0);
            $table->decimal('medical_allowed', 5, 1)->default(0);
            $table->decimal('casual_taken', 5, 1)->default(0);
            $table->decimal('medical_taken', 5, 1)->default(0);
        });
    }
};

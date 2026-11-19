<?php

declare(strict_types=1);

use App\Models\ErpModule;
use App\Models\ErpSubModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('erp_modules') || ! Schema::hasTable('erp_sub_modules')) {
            return;
        }

        $module = ErpModule::where('code', 'admin_users_roles')->first();
        if (! $module) {
            return;
        }

        $exists = ErpSubModule::where('erp_module_id', $module->id)
            ->where('code', 'activity_logs.manage')
            ->exists();

        if (! $exists) {
            $maxOrder = (int) ErpSubModule::where('erp_module_id', $module->id)->max('order');

            ErpSubModule::create([
                'erp_module_id' => $module->id,
                'name' => 'Activity Logs',
                'code' => 'activity_logs.manage',
                'route_name' => 'admin.activity-logs.index',
                'actions' => ['view', 'delete', 'excel', 'pdf'],
                'description' => 'Employee activity, presence timeline, exports, and recorded changes audit',
                'order' => $maxOrder + 1,
                'status' => 'active',
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('erp_sub_modules')) {
            return;
        }

        ErpSubModule::where('code', 'activity_logs.manage')->delete();
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $actions = ['view', 'create', 'edit', 'delete', 'excel'];

        DB::table('erp_sub_modules')
            ->where('code', 'office_rent.manage')
            ->update([
                'actions' => json_encode($actions),
                'updated_at' => $now,
            ]);

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
        $actions = ['view', 'create', 'edit', 'delete'];
        DB::table('erp_sub_modules')
            ->where('code', 'office_rent.manage')
            ->update([
                'actions' => json_encode($actions),
                'updated_at' => now(),
            ]);
    }
};

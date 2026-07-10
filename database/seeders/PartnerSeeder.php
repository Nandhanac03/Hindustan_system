<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PartnerSeeder extends Seeder
{
    /**
     * Seed Basheer and Pavoor as real partners in the DB.
     * Also seeds default partner shares (50/50) on project 1.
     */
    public function run(): void
    {
        $systemId = 1; // India System
        $projectId = 1; // Tabasco Hindustan Infra Developers Pvt.Ltd
        $now = Carbon::now();

        // ─────────────────────────────────────────
        // 1. Create linked Current Accounts
        //    (table name without prefix — Laravel adds prefix automatically)
        // ─────────────────────────────────────────
        $basheerAccountId = DB::table('accounts')->insertGetId([
            'system_id'  => $systemId,
            'type'       => 'liability',
            'name'       => 'Basheer',
            'code'       => 'PRT-ACC-03',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $pavoorAccountId = DB::table('accounts')->insertGetId([
            'system_id'  => $systemId,
            'type'       => 'liability',
            'name'       => 'Pavoor',
            'code'       => 'PRT-ACC-04',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ─────────────────────────────────────────
        // 2. Create Partner Payees
        // ─────────────────────────────────────────
        $basheerPayeeId = DB::table('payees')->insertGetId([
            'system_id'         => $systemId,
            'type'              => 'Partner',
            'name'              => 'Basheer',
            'linked_account_id' => $basheerAccountId,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        $pavoorPayeeId = DB::table('payees')->insertGetId([
            'system_id'         => $systemId,
            'type'              => 'Partner',
            'name'              => 'Pavoor',
            'linked_account_id' => $pavoorAccountId,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);

        // ─────────────────────────────────────────
        // 3. Set default partner shares (50/50) on project 1
        //    Only if no shares exist for these partners yet
        // ─────────────────────────────────────────
        $existsBasheerShare = DB::table('partner_shares')
            ->where('partner_id', $basheerPayeeId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $existsBasheerShare) {
            DB::table('partner_shares')->insert([
                'system_id'  => $systemId,
                'partner_id' => $basheerPayeeId,
                'project_id' => $projectId,
                'share_pct'  => 57.50,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $existsPavoorShare = DB::table('partner_shares')
            ->where('partner_id', $pavoorPayeeId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $existsPavoorShare) {
            DB::table('partner_shares')->insert([
                'system_id'  => $systemId,
                'partner_id' => $pavoorPayeeId,
                'project_id' => $projectId,
                'share_pct'  => 42.50,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info("✅ Basheer (payee #{$basheerPayeeId}) added with account #{$basheerAccountId}");
        $this->command->info("✅ Pavoor (payee #{$pavoorPayeeId}) added with account #{$pavoorAccountId}");
        $this->command->info("✅ Partner shares (50/50) seeded for project #{$projectId}");
    }
}

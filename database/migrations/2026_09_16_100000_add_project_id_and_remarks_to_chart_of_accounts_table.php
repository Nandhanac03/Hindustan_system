<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('chart_of_accounts', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('account_type')->constrained('projects')->nullOnDelete();
            }
            if (!Schema::hasColumn('chart_of_accounts', 'remarks')) {
                $table->string('remarks', 255)->nullable()->after('opening_balance_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('chart_of_accounts', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('chart_of_accounts', 'remarks')) {
                $table->dropColumn('remarks');
            }
        });
    }
};

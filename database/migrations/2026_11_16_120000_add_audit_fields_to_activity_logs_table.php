<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_logs', 'category')) {
                $table->string('category', 40)->nullable()->index()->after('action');
            }
            if (! Schema::hasColumn('activity_logs', 'screen')) {
                $table->string('screen')->nullable()->after('category');
            }
            if (! Schema::hasColumn('activity_logs', 'route_name')) {
                $table->string('route_name')->nullable()->after('screen');
            }
            if (! Schema::hasColumn('activity_logs', 'method')) {
                $table->string('method', 12)->nullable()->after('route_name');
            }
            if (! Schema::hasColumn('activity_logs', 'module')) {
                $table->string('module', 100)->nullable()->after('method');
            }
            if (! Schema::hasColumn('activity_logs', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable()->after('module');
            }
            if (! Schema::hasColumn('activity_logs', 'metadata')) {
                $table->json('metadata')->nullable()->after('user_agent');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            $cols = array_filter([
                'category',
                'screen',
                'route_name',
                'method',
                'module',
                'duration_seconds',
                'metadata',
            ], fn (string $col) => Schema::hasColumn('activity_logs', $col));

            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};


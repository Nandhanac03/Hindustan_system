<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('dms_documents', 'is_archived')) {
            Schema::table('dms_documents', function (Blueprint $table) {
                $table->boolean('is_archived')->default(false)->after('reference_project_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dms_documents', 'is_archived')) {
            Schema::table('dms_documents', function (Blueprint $table) {
                $table->dropColumn('is_archived');
            });
        }
    }
};

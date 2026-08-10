<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('receipts') && !Schema::hasColumn('receipts', 'is_allocated')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->boolean('is_allocated')->default(false)->after('amount');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('receipts') && Schema::hasColumn('receipts', 'is_allocated')) {
            Schema::table('receipts', function (Blueprint $table) {
                $table->dropColumn('is_allocated');
            });
        }
    }
};

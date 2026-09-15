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
        Schema::table('journal_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('journal_entries', 'entity_type')) {
                $table->enum('entity_type', ['CUSTOMER', 'SUPPLIER', 'CONTRACTOR', 'BANK', 'AGENT', 'PARTNER', 'OTHER'])->nullable()->after('line_narration');
            }
            if (!Schema::hasColumn('journal_entries', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
                $table->index(['entity_type', 'entity_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            if (Schema::hasColumn('journal_entries', 'entity_type')) {
                $table->dropColumn('entity_type');
            }
            if (Schema::hasColumn('journal_entries', 'entity_id')) {
                $table->dropIndex(['entity_type', 'entity_id']);
                $table->dropColumn('entity_id');
            }
        });
    }
};

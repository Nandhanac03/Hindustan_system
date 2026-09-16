<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('accounting_settings')) {
            Schema::create('accounting_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->timestamp('locked_at')->nullable();
                $table->foreignId('locked_by')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('accounting_settings')) {
            DB::table('accounting_settings')->updateOrInsert(
                ['key' => 'is_opening_balance_locked'],
                [
                    'value' => '0',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounting_settings');
    }
};

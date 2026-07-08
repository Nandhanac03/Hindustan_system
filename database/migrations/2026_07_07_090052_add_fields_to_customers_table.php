<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasColumn('customers', 'address')) {
                $table->text('address')->nullable();
            }
            if (! Schema::hasColumn('customers', 'id_proof_type')) {
                $table->string('id_proof_type', 50)->nullable();
            }
            if (! Schema::hasColumn('customers', 'id_proof_number')) {
                $table->string('id_proof_number', 50)->nullable();
            }
            if (! Schema::hasColumn('customers', 'system')) {
                $table->enum('system', ['india', 'uae'])->default('india');
            }
            if (! Schema::hasColumn('customers', 'is_active')) {
                $table->boolean('is_active')->default(1);
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['address', 'id_proof_type', 'id_proof_number', 'system', 'is_active']);
        });
    }
};
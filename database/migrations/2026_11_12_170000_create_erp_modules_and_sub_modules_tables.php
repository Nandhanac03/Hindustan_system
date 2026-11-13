<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('erp_modules')) {
            Schema::create('erp_modules', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('code', 50)->unique();
                $table->string('section', 100);
                $table->string('icon', 50)->default('folder');
                $table->string('description', 255)->nullable();
                $table->integer('order')->default(0);
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_sub_modules')) {
            Schema::create('erp_sub_modules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('erp_module_id')->constrained('erp_modules')->cascadeOnDelete();
                $table->string('name', 100);
                $table->string('code', 50);
                $table->string('route_name', 100)->nullable();
                $table->json('actions')->nullable();
                $table->string('description', 255)->nullable();
                $table->integer('order')->default(0);
                $table->string('status', 20)->default('active');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('erp_sub_modules');
        Schema::dropIfExists('erp_modules');
    }
};


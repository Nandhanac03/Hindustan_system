<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. projects table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained('systems')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('location');
            $table->string('city');
            $table->string('state_or_emirate');
            $table->string('country');
            $table->string('rera_number')->nullable();
            $table->integer('total_floors');
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->string('status')->default('planning'); // planning, ongoing, completed, on_hold
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['system_id', 'code']);
        });

        // 2. floors table
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->integer('floor_number');
            $table->string('name');
            $table->timestamps();

            $table->unique(['project_id', 'floor_number']);
        });

        // 3. unit_types table
        Schema::create('unit_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // residential, commercial, parking
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_types');
        Schema::dropIfExists('floors');
        Schema::dropIfExists('projects');
    }
};

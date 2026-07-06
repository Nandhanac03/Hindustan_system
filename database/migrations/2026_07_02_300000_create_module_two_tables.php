<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key constraints to drop existing stub tables safely
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('units');
        Schema::dropIfExists('projects');
        Schema::enableForeignKeyConstraints();

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

        // 4. units table
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('floor_id')->constrained('floors')->cascadeOnDelete();
            $table->foreignId('unit_type_id')->constrained('unit_types')->cascadeOnDelete();
            $table->string('unit_number');
            $table->decimal('bua_area', 10, 2);
            $table->decimal('carpet_area', 10, 2)->nullable();
            $table->string('area_unit')->default('sqft'); // sqft, sqm
            $table->string('facing')->nullable();
            $table->string('status')->default('available'); // available, blocked, booked, sold, on_hold
            $table->decimal('base_rate', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['project_id', 'unit_number']);
        });

        // 5. unit_rate_logs table
        Schema::create('unit_rate_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->decimal('rate', 15, 2);
            $table->date('effective_from');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        // 6. unit_status_logs table
        Schema::create('unit_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_status_logs');
        Schema::dropIfExists('unit_rate_logs');
        Schema::dropIfExists('units');
        Schema::dropIfExists('unit_types');
        Schema::dropIfExists('floors');
        Schema::dropIfExists('projects');
    }
};

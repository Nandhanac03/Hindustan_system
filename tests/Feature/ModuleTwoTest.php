<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Floor;
use App\Models\Project;
use App\Models\System;
use App\Models\Unit;
use App\Models\UnitType;
use App\Models\User;
use App\Services\UnitStatusService;
use App\Services\UnitRateService;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ModuleTwoTest extends TestCase
{
    use RefreshDatabase;

    protected UnitStatusService $statusService;
    protected UnitRateService $rateService;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles/permissions & systems
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SystemSeeder::class);

        $this->statusService = app(UnitStatusService::class);
        $this->rateService = app(UnitRateService::class);
    }

    /** @test */
    public function it_can_bulk_generate_floors_and_units_correctly(): void
    {
        $india = System::where('code', 'IN')->first();
        $owner = User::where('email', 'owner@hindustan.com')->first();
        $this->actingAs($owner);

        $project = Project::create([
            'system_id' => $india->id,
            'name' => 'Emerald Rise',
            'code' => 'EMR-01',
            'location' => 'Sector 150',
            'city' => 'Noida',
            'state_or_emirate' => 'UP',
            'country' => 'India',
            'total_floors' => 5,
            'status' => 'planning',
        ]);

        $unitType = UnitType::first();

        // Perform bulk generate POST
        $response = $this->post(route('projects.bulk-generate.store', $project->id), [
            'start_floor' => 1,
            'end_floor' => 5,
            'units_per_floor' => 4,
            'unit_type_id' => $unitType->id,
            'unit_prefix' => 'E-',
            'bua_area' => 1500,
            'base_rate' => 5000,
            'facing' => 'East',
        ]);

        $response->assertRedirect(route('projects.show', $project->id));

        // Assert 5 floors and 20 units created
        $this->assertEquals(5, Floor::where('project_id', $project->id)->count());
        $this->assertEquals(20, Unit::where('project_id', $project->id)->count());

        // Assert unique unit number: e.g. E-101, E-504
        $this->assertDatabaseHas('hindustan_units', [
            'project_id' => $project->id,
            'door_no' => 'E-101',
            'status' => 'available',
        ]);
        $this->assertDatabaseHas('hindustan_units', [
            'project_id' => $project->id,
            'door_no' => 'E-504',
            'status' => 'available',
        ]);
    }

    /** @test */
    public function it_rejects_invalid_status_transitions(): void
    {
        $india = System::where('code', 'IN')->first();
        $project = Project::create([
            'system_id' => $india->id,
            'name' => 'Emerald Rise',
            'code' => 'EMR-01',
            'location' => 'Sector 150',
            'city' => 'Noida',
            'state_or_emirate' => 'UP',
            'country' => 'India',
            'total_floors' => 2,
        ]);

        $floor = Floor::create([
            'project_id' => $project->id,
            'floor_number' => 1,
            'name' => 'Floor 1',
        ]);

        $unit = Unit::create([
            'project_id' => $project->id,
            'floor_id' => $floor->id,
            'unit_type_id' => UnitType::first()->id,
            'door_no' => 'A-101',
            'built_up_area' => 1000,
            'status' => 'available',
            'expected_rate_per_sqft' => 4500,
            'expected_sale_amount' => 4500000,
        ]);

        // Attempt invalid status transition: available directly to sold
        $this->expectException(\InvalidArgumentException::class);
        $this->statusService->transitionTo($unit, 'sold');
    }

    /** @test */
    public function it_asserts_concurrency_conflict_throws_error(): void
    {
        $india = System::where('code', 'IN')->first();
        $project = Project::create([
            'system_id' => $india->id,
            'name' => 'Emerald Rise',
            'code' => 'EMR-01',
            'location' => 'Sector 150',
            'city' => 'Noida',
            'state_or_emirate' => 'UP',
            'country' => 'India',
            'total_floors' => 2,
        ]);

        $floor = Floor::create([
            'project_id' => $project->id,
            'floor_number' => 1,
            'name' => 'Floor 1',
        ]);

        $unit = Unit::create([
            'project_id' => $project->id,
            'floor_id' => $floor->id,
            'unit_type_id' => UnitType::first()->id,
            'door_no' => 'A-101',
            'built_up_area' => 1000,
            'status' => 'available',
            'expected_rate_per_sqft' => 4500,
            'expected_sale_amount' => 4500000,
        ]);

        // Create two model instances representing concurrent memory states
        $instanceA = Unit::find($unit->id);
        $instanceB = Unit::find($unit->id);

        // Session A updates to blocked
        $this->statusService->transitionTo($instanceA, 'blocked', 'Blocked by User A');

        // Session B tries to update from stale state (available) to blocked
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Concurrency conflict');

        $this->statusService->transitionTo($instanceB, 'blocked', 'Blocked by User B');
    }

    /** @test */
    public function it_isolates_projects_by_system_scope(): void
    {
        $india = System::where('code', 'IN')->first();
        $uae = System::where('code', 'AE')->first();

        // Create Indian Project
        $indiaProject = Project::create([
            'system_id' => $india->id,
            'name' => 'Hindustan Delhi',
            'code' => 'DEL-01',
            'location' => 'Dwarka',
            'city' => 'Delhi',
            'state_or_emirate' => 'Delhi',
            'country' => 'India',
            'total_floors' => 5,
        ]);

        // Create UAE Project
        $uaeProject = Project::create([
            'system_id' => $uae->id,
            'name' => 'Hindustan Dubai Marina',
            'code' => 'DXB-01',
            'location' => 'Marina',
            'city' => 'Dubai',
            'state_or_emirate' => 'Dubai',
            'country' => 'UAE',
            'total_floors' => 5,
        ]);

        // Log in as Accountant in India system
        $accountantIn = User::create([
            'name' => 'Rajesh Accountant',
            'email' => 'rajesh@company.com',
            'password' => bcrypt('password'),
            'system_id' => $india->id,
            'status' => 'active',
        ]);
        $accountantIn->assignRole('Accountant');

        $this->actingAs($accountantIn);

        // Fetch projects - Indian project should be visible, UAE project should NOT
        $indiaProjects = Project::all();
        $this->assertTrue($indiaProjects->contains($indiaProject));
        $this->assertFalse($indiaProjects->contains($uaeProject));
    }

    /** @test */
    public function it_blocks_deletion_of_non_available_units(): void
    {
        $india = System::where('code', 'IN')->first();
        $owner = User::where('email', 'owner@hindustan.com')->first();
        $this->actingAs($owner);

        $project = Project::create([
            'system_id' => $india->id,
            'name' => 'Emerald Rise',
            'code' => 'EMR-01',
            'location' => 'Sector 150',
            'city' => 'Noida',
            'state_or_emirate' => 'UP',
            'country' => 'India',
            'total_floors' => 2,
        ]);

        $floor = Floor::create([
            'project_id' => $project->id,
            'floor_number' => 1,
            'name' => 'Floor 1',
        ]);

        $unit = Unit::create([
            'project_id' => $project->id,
            'floor_id' => $floor->id,
            'unit_type_id' => UnitType::first()->id,
            'door_no' => 'A-101',
            'built_up_area' => 1000,
            'status' => 'blocked',
            'expected_rate_per_sqft' => 4500,
            'expected_sale_amount' => 4500000,
        ]);

        // Attempt delete on 'blocked' unit
        $response = $this->deleteJson(route('units.destroy', $unit->id));
        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => 'Only units with available status can be deleted.']);

        $this->assertDatabaseHas('hindustan_units', ['id' => $unit->id]);

        // Move to available, delete succeeds
        $unit->update(['status' => 'available']);
        $response2 = $this->deleteJson(route('units.destroy', $unit->id));
        $response2->assertStatus(200);

        $this->assertDatabaseMissing('hindustan_units', ['id' => $unit->id]);
    }

    /** @test */
    public function it_can_bulk_add_units_via_ajax(): void
    {
        $india = System::where('code', 'IN')->first();
        $owner = User::where('email', 'owner@hindustan.com')->first();
        $this->actingAs($owner);

        $project = Project::create([
            'system_id' => $india->id,
            'name' => 'Emerald Rise',
            'code' => 'EMR-01',
            'location' => 'Sector 150',
            'city' => 'Noida',
            'state_or_emirate' => 'UP',
            'country' => 'India',
            'total_floors' => 2,
        ]);

        $floor = Floor::create([
            'project_id' => $project->id,
            'floor_number' => 1,
            'name' => 'Floor 1',
        ]);

        $unitType = UnitType::first();

        $response = $this->postJson(route('units.bulk-store'), [
            'project_id' => $project->id,
            'floor_id' => $floor->id,
            'unit_type_id' => $unitType->id,
            'unit_prefix' => 'B-',
            'start_number' => 10,
            'count' => 3,
            'built_up_area' => 1250,
            'expected_rate_per_sqft' => 4800,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'count' => 3]);

        $this->assertEquals(3, Unit::where('project_id', $project->id)->count());
        $this->assertDatabaseHas('hindustan_units', ['door_no' => 'B-10']);
        $this->assertDatabaseHas('hindustan_units', ['door_no' => 'B-11']);
        $this->assertDatabaseHas('hindustan_units', ['door_no' => 'B-12']);
    }
}

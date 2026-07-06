<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Approval;
use App\Models\System;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ModuleOneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run seeders for testing environment
        $this->seed(RolePermissionSeeder::class);
        $this->seed(SystemSeeder::class);
    }

    /** @test */
    public function owner_can_log_in_and_redirect_to_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'owner@hindustan.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function system_scope_isolates_users_based_on_system_id(): void
    {
        $india = System::where('code', 'IN')->first();
        $uae = System::where('code', 'AE')->first();

        // India Accountant
        $accountantIn = User::create([
            'name' => 'Rajesh Accountant',
            'email' => 'rajesh.acc@hindustan.com',
            'password' => Hash::make('password'),
            'system_id' => $india->id,
            'status' => 'active',
        ]);
        $accountantIn->assignRole('Accountant');

        // UAE Sales Rep
        $salesUae = User::create([
            'name' => 'Omar UAE',
            'email' => 'omar.uae@hindustan.com',
            'password' => Hash::make('password'),
            'system_id' => $uae->id,
            'status' => 'active',
            'employee_code' => 'EMP-UAE-01',
        ]);
        $salesUae->assignRole('Sales');

        // Log in as India Accountant
        $this->actingAs($accountantIn);

        // Fetch users list index
        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        // Rajesh Accountant (India) should be visible
        $response->assertSee('Rajesh Accountant');
        // Omar UAE (UAE) should NOT be visible
        $response->assertDontSee('Omar UAE');
    }

    /** @test */
    public function approval_approve_and_reject_methods_stamp_correctly(): void
    {
        $india = System::where('code', 'IN')->first();

        $requester = User::create([
            'name' => 'Requestor User',
            'email' => 'req@hindustan.com',
            'password' => Hash::make('password'),
            'system_id' => $india->id,
            'status' => 'active',
        ]);

        $approver = User::where('email', 'owner@hindustan.com')->first();

        // Create approval mock using morph parameters pointing to systems/requester
        $approval = Approval::create([
            'approvable_type' => System::class,
            'approvable_id' => $india->id,
            'requested_by' => $requester->id,
            'status' => 'pending',
        ]);

        // Test Approval approve() method
        $approval->approve($approver, 'Approved this request successfully.');

        $this->assertEquals('approved', $approval->status);
        $this->assertEquals($approver->id, $approval->approved_by);
        $this->assertEquals('Approved this request successfully.', $approval->reason);
        $this->assertNotNull($approval->approved_at);

        // Test Approval reject() method
        $approval->reject($approver, 'Rejected due to validation error.');

        $this->assertEquals('rejected', $approval->status);
        $this->assertEquals($approver->id, $approval->approved_by);
        $this->assertEquals('Rejected due to validation error.', $approval->reason);
    }
}

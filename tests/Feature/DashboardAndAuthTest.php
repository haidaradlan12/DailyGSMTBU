<?php

use App\Models\Cost;
use App\Models\Dorcat;
use App\Models\Transport;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->seed();
});

test('public dashboard loads successfully with initial metrics', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Daily Monitoring GS MTBU');
    $response->assertSee('Cost Tiket - 2351');
    $response->assertSee('Cost NPE - 2402');
    $response->assertSee('Office Expense - 2403');
    $response->assertSee('Mess Expense - 2405');
    $response->assertSee('Cost Building Maint - 2410');
    $response->assertSee('Dorminoty & Catering', false);
    $response->assertSee('Transport');
    $response->assertSee('PA Bus');
    $response->assertSee('PA LV');
    $response->assertSee('Coverage Pengantaran');
    $response->assertSee('Target 99%');
    $response->assertSee('Target 98%');
    $response->assertSee('Target 100%');
    $response->assertSee('Fuel Rasio Bus');
    $response->assertSee('Fuel Rasio LV');
    $response->assertSee('Issue');
    $response->assertSee('MEP & Instrument', false);
    $response->assertSee('Building Const');
});

test('public dashboard api data endpoint returns json structure', function () {
    $response = $this->get('/api/dashboard-data');
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'selectedDate',
        'costs',
        'dorcatIssues',
        'transport',
        'mepOrders',
        'sipilOrders',
        'mepHotIssue',
        'sipilHotIssue',
    ]);
});

test('user registration creates an unapproved account', function () {
    $response = $this->post('/register', [
        'name' => 'John Operator',
        'email' => 'john@gsmtbu.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect('/login');

    $user = User::where('email', 'john@gsmtbu.com')->first();
    expect($user)->not->toBeNull();
    expect($user->is_approved)->toBeFalse();
    expect($user->role)->toBe('operator');
});

test('unapproved user cannot login', function () {
    User::create([
        'name' => 'Pending Guy',
        'email' => 'pending@gsmtbu.com',
        'password' => Hash::make('Password123!'),
        'role' => 'operator',
        'is_approved' => false,
    ]);

    $response = $this->post('/login', [
        'email' => 'pending@gsmtbu.com',
        'password' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('approved super admin can login and access admin dashboard', function () {
    $admin = User::where('email', 'admin@gsmtbu.com')->first();
    if (! $admin) {
        $admin = User::create([
            'name' => 'Admin Utama GS MTBU',
            'email' => 'admin@gsmtbu.com',
            'password' => Hash::make('Password123!'),
            'role' => 'super_admin',
            'is_approved' => true,
            'approved_at' => now(),
        ]);
    }

    $response = $this->post('/login', [
        'email' => 'admin@gsmtbu.com',
        'password' => 'Password123!',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($admin);

    $adminDashboard = $this->actingAs($admin)->get('/admin');
    $adminDashboard->assertStatus(200);
    $adminDashboard->assertSee('Ringkasan Administrasi');
});

test('super admin can approve a pending user', function () {
    $admin = User::where('role', 'super_admin')->first();

    $pending = User::create([
        'name' => 'Needs Approval',
        'email' => 'needs@gsmtbu.com',
        'password' => Hash::make('Password123!'),
        'role' => 'operator',
        'is_approved' => false,
    ]);

    $response = $this->actingAs($admin)->post("/admin/users/{$pending->id}/approve");
    $response->assertSessionHas('success');

    expect($pending->fresh()->is_approved)->toBeTrue();
    expect($pending->fresh()->approved_by)->toBe($admin->id);
});

test('admin can update transport PA and ATR metrics', function () {
    $admin = User::where('role', 'super_admin')->first();
    $today = now()->toDateString();

    $response = $this->actingAs($admin)->post('/admin/transport', [
        'date' => $today,
        'pa_bus' => 98.0,
        'pa_lv' => 97.5,
        'coverage_pengantaran' => 99.1,
        'atr_lv' => 91.2,
        'unit_ready_lv' => 40,
        'unit_breakdown_lv' => 1,
        'unit_ready_bus' => 12,
        'unit_breakdown_bus' => 0,
        'notes' => 'Armada siap operasional penuh.',
    ]);

    $response->assertRedirect('/admin/transport?date=' . $today);

    $transport = Transport::where('date', $today)->first();
    expect($transport)->not->toBeNull();
    expect((float) $transport->pa_bus)->toBe(98.0);
    expect((float) $transport->pa_lv)->toBe(97.5);
    expect((float) $transport->coverage_pengantaran)->toBe(99.1);
});

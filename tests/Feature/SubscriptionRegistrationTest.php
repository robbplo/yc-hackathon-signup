<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('requires a plan to be selected during registration', function () {
    post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ])->assertInvalid(['plan']);
});

it('validates that plan is one of the allowed values', function () {
    post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'plan' => 'invalid_plan',
    ])->assertInvalid(['plan']);
});

it('allows registration with basic monthly plan', function () {
    $response = post('/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'plan' => 'basic_monthly',
    ]);

    $response->assertRedirect();

    assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'selected_plan' => 'basic_monthly',
    ]);
});

it('allows registration with pro monthly plan', function () {
    post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'plan' => 'pro_monthly',
    ]);

    assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'selected_plan' => 'pro_monthly',
    ]);
});

it('allows registration with enterprise monthly plan', function () {
    post('/register', [
        'name' => 'Enterprise User',
        'email' => 'enterprise@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'plan' => 'enterprise_monthly',
    ]);

    assertDatabaseHas('users', [
        'email' => 'enterprise@example.com',
        'selected_plan' => 'enterprise_monthly',
    ]);
});

it('shows subscription checkout page for users with selected plan but no subscription', function () {
    $user = User::factory()->create([
        'selected_plan' => 'basic_monthly',
    ]);

    actingAs($user)
        ->get('/subscription/checkout')
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('subscription/Checkout')
            ->where('plan', 'basic_monthly')
        );
});

it('redirects to subscription checkout if user has no subscription', function () {
    $user = User::factory()->create([
        'selected_plan' => 'pro_monthly',
    ]);

    actingAs($user)
        ->get('/dashboard')
        ->assertRedirect('/subscription/checkout');
});

it('redirects to register if user has no selected plan', function () {
    $user = User::factory()->create([
        'selected_plan' => null,
    ]);

    actingAs($user)
        ->get('/subscription/checkout')
        ->assertRedirect('/register');
});

it('allows access to dashboard for users with active subscription', function () {
    $user = User::factory()->create([
        'selected_plan' => 'basic_monthly',
    ]);

    // Mock the subscription
    $this->mock(\Laravel\Cashier\Subscription::class);

    // Manually create a subscription record for testing
    \Illuminate\Support\Facades\DB::table('subscriptions')->insert([
        'name' => 'default',
        'plan' => 'basic_monthly',
        'owner_type' => User::class,
        'owner_id' => $user->id,
        'quantity' => 1,
        'tax_percentage' => 0,
        'trial_ends_at' => null,
        'ends_at' => null,
        'cycle_started_at' => now(),
        'cycle_ends_at' => now()->addMonth(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    actingAs($user)
        ->get('/dashboard')
        ->assertSuccessful();
});

<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;

test('to array includes computed name', function (): void {
    $user = User::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ])->refresh();

    expect($user->toArray())
        ->toHaveKeys(['id', 'first_name', 'last_name', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at'])
        ->and($user->name)->toBe('Jane Doe')
        ->and($user->id)->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
});

test('admin factory assigns Admin role', function (): void {
    $user = User::factory()->admin()->create();

    expect($user->hasRole('Admin'))->toBeTrue();
});

test('admins can access admin and app panels', function (): void {
    $user = User::factory()->admin()->create();

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue()
        ->and($user->canAccessPanel(Filament::getPanel('app')))->toBeTrue();
});

test('non-admins can access the app panel only', function (): void {
    $user = User::factory()->create();

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeFalse()
        ->and($user->canAccessPanel(Filament::getPanel('app')))->toBeTrue()
        ->and($user->canAccessPanel((new Panel)->id('other')))->toBeFalse();
});

test('only admins can impersonate', function (): void {
    expect(User::factory()->admin()->create()->canImpersonate())->toBeTrue()
        ->and(User::factory()->create()->canImpersonate())->toBeFalse()
        ->and(User::factory()->create()->canBeImpersonated())->toBeTrue();
});

test('users must verify email', function (): void {
    $user = User::factory()->unverified()->create();

    expect($user)->toBeInstanceOf(MustVerifyEmail::class)
        ->and($user->hasVerifiedEmail())->toBeFalse();
});

test('audits exclude password and remember token', function (): void {
    config(['audit.console' => true]);

    $user = User::factory()->create();

    $user->update([
        'first_name' => 'Changed',
        'password' => 'new-password',
        'remember_token' => 'secret-token',
    ]);

    $audit = $user->audits()->latest('id')->first();

    expect($audit)->not->toBeNull()
        ->and($audit->new_values)->toHaveKey('first_name')
        ->and($audit->new_values)->not->toHaveKeys(['password', 'remember_token'])
        ->and($audit->old_values)->not->toHaveKeys(['password', 'remember_token']);
});

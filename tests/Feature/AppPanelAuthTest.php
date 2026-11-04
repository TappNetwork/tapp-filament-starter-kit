<?php

declare(strict_types=1);

use App\Filament\App\Pages\Auth\EditProfile;
use App\Filament\App\Pages\Auth\Register;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

it('serves login, registration, and password reset on the app panel', function (): void {
    get('/app/login')->assertOk();
    get('/app/register')->assertOk();
    get('/app/password-reset/request')->assertOk();
});

it('does not serve auth pages on the admin panel', function (): void {
    get('/admin/login')->assertNotFound();
    get('/admin/register')->assertNotFound();
    get('/admin/password-reset/request')->assertNotFound();
});

it('redirects guests from the app panel to app login', function (): void {
    get('/app')->assertRedirect(route('filament.app.auth.login'));
});

it('redirects guests from the admin panel to app login', function (): void {
    get('/admin')->assertRedirect(route('filament.app.auth.login'));
});

it('allows any verified user on the app panel', function (): void {
    actingAs(User::factory()->create());

    get('/app')->assertOk();
});

it('prompts unverified users to verify their email', function (): void {
    actingAs(User::factory()->unverified()->create());

    get('/app')->assertRedirect(route('filament.app.auth.email-verification.prompt'));
});

it('forbids non-admins from the admin panel', function (): void {
    actingAs(User::factory()->create());

    get('/admin')->assertForbidden();
});

it('allows admins on the admin panel', function (): void {
    seed(RoleSeeder::class);

    actingAs(User::factory()->admin()->create());

    get('/admin')->assertOk();
});

it('registers a user and sends a verification email', function (): void {
    Notification::fake();

    Filament::setCurrentPanel('app');

    livewire(Register::class)
        ->fillForm([
            'first_name' => 'New',
            'last_name' => 'Person',
            'email' => 'new@example.com',
            'password' => 'password',
            'passwordConfirmation' => 'password',
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    $user = User::query()->where('email', 'new@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->first_name)->toBe('New')
        ->and($user->last_name)->toBe('Person')
        ->and($user->hasVerifiedEmail())->toBeFalse();

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('requires registration fields', function (): void {
    Filament::setCurrentPanel('app');

    livewire(Register::class)
        ->fillForm([
            'first_name' => null,
            'last_name' => null,
            'email' => 'invalid-email',
            'password' => null,
            'passwordConfirmation' => null,
        ])
        ->call('register')
        ->assertHasFormErrors([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email',
            'password' => 'required',
        ])
        ->assertNotNotified();

    assertGuest();
});

it('does not serve registration when it is disabled', function (): void {
    config(['filament-panels.registration' => false]);

    Filament::setCurrentPanel('app');

    get('/app/register')->assertNotFound();

    livewire(Register::class)->assertNotFound();
});

it('builds password reset urls for the app panel', function (): void {
    $user = User::factory()->create();

    $url = $user->getResetPasswordUrl('test-token');

    expect($url)
        ->toContain('/app/password-reset')
        ->and($url)->not->toContain('/admin/');
});

it('does not register a login url on the admin panel', function (): void {
    expect(Filament::getPanel('admin')->getLoginUrl())->toBeNull()
        ->and(Filament::getPanel('app')->getLoginUrl())->not->toBeNull();
});

it('shows first and last name on the profile form', function (): void {
    Filament::setCurrentPanel('app');

    $user = User::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    actingAs($user);

    livewire(EditProfile::class)
        ->assertFormSet([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => $user->email,
        ]);
});

it('updates the profile name', function (): void {
    Filament::setCurrentPanel('app');

    $user = User::factory()->create();

    actingAs($user);

    livewire(EditProfile::class)
        ->fillForm([
            'first_name' => 'Updated',
            'last_name' => 'Person',
            'email' => $user->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($user->refresh())
        ->first_name->toBe('Updated')
        ->last_name->toBe('Person');
});

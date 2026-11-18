<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Laravel\Sanctum\PersonalAccessToken;
use Tapp\FilamentMcp\Pages\McpTokens;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Laravel\travelTo;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    seed(RoleSeeder::class);

    Filament::setCurrentPanel('admin');
});

it('redirects guests from the token page to app login', function (): void {
    expect(McpTokens::canAccess())->toBeFalse();

    get('/admin/api-tokens')->assertRedirect(route('filament.app.auth.login'));
});

it('forbids users without the admin role from the token page', function (): void {
    actingAs(User::factory()->create());

    expect(McpTokens::canAccess())->toBeFalse();

    get('/admin/api-tokens')->assertForbidden();
});

it('shows the token page to admins', function (): void {
    actingAs(User::factory()->admin()->create());

    expect(McpTokens::canAccess())->toBeTrue();

    get('/admin/api-tokens')
        ->assertOk()
        ->assertSee('API tokens');
});

it('does not serve the token page on the app panel', function (): void {
    actingAs(User::factory()->admin()->create());

    get('/app/api-tokens')->assertNotFound();
});

it('creates a token for the signed-in admin and shows it once', function (): void {
    travelTo('2026-01-01 00:00:00');

    $admin = User::factory()->admin()->create();

    actingAs($admin);

    $component = livewire(McpTokens::class)
        ->callAction(TestAction::make('new'), [
            'name' => 'Cursor',
            'expiration' => '30',
            'abilities' => ['application-summary-tool'],
        ])
        ->assertNotified()
        ->assertHasNoActionErrors();

    $plainTextToken = $component->instance()->plainTextToken;

    expect($plainTextToken)->toBeString()->toContain('|');

    $stored = $admin->tokens()->first();

    expect($stored)->toBeInstanceOf(PersonalAccessToken::class)
        ->and($stored->name)->toBe('Cursor')
        ->and($stored->abilities)->toBe(['application-summary-tool'])
        ->and($stored->expires_at?->toDateTimeString())->toBe('2026-01-31 00:00:00');

    [$id, $secret] = explode('|', $plainTextToken, 2);

    expect($stored->getKey())->toBe((int) $id)
        ->and($stored->token)->toBe(hash('sha256', $secret));

    $component->call('forgetPlainTextToken')
        ->assertSet('plainTextToken', null);
});

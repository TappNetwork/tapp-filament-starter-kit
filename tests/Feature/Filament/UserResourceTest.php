<?php

declare(strict_types=1);

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    seed(RoleSeeder::class);

    Filament::setCurrentPanel('admin');

    actingAs(User::factory()->admin()->create());
});

it('lists users in the admin panel', function (): void {
    $users = User::factory()->count(2)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('creates a user with roles', function (): void {
    $role = Role::query()->where('name', 'Admin')->first();

    livewire(CreateUser::class)
        ->fillForm([
            'first_name' => 'New',
            'last_name' => 'User',
            'email' => 'new@example.com',
            'password' => 'password',
            'roles' => [$role->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas(User::class, [
        'first_name' => 'New',
        'last_name' => 'User',
        'email' => 'new@example.com',
    ]);
});

it('updates a user', function (): void {
    $role = Role::query()->where('name', 'Admin')->first();

    $user = User::factory()->create([
        'first_name' => 'Original',
        'last_name' => 'Name',
    ]);

    livewire(EditUser::class, ['record' => $user->id])
        ->fillForm([
            'first_name' => 'Updated',
            'roles' => [$role->id],
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    assertDatabaseHas(User::class, [
        'id' => $user->id,
        'first_name' => 'Updated',
    ]);
});

<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\assertModelExists;
use function Pest\Laravel\seed;

it('seeds a local admin with a generated password when ADMIN_PASSWORD is blank', function (?string $password, ?string $email): void {
    config([
        'admin.email' => $email,
        'admin.password' => $password,
    ]);

    seed(DatabaseSeeder::class);

    $user = User::query()->where('email', 'admin@dev.com')->first();

    expect($user)->toBeInstanceOf(User::class);

    assertModelExists($user);

    expect($user->hasRole('Admin'))->toBeTrue()
        ->and($user->hasVerifiedEmail())->toBeTrue()
        ->and(Hash::check('secret', $user->password))->toBeFalse()
        ->and(Hash::check('', $user->password))->toBeFalse();
})->with([
    'missing' => [null, null],
    'empty' => ['', ''],
]);

it('seeds the local admin from ADMIN_EMAIL and ADMIN_PASSWORD', function (): void {
    config([
        'admin.email' => 'owner@example.com',
        'admin.password' => 'provided-password',
    ]);

    seed(DatabaseSeeder::class);

    $user = User::query()->where('email', 'owner@example.com')->first();

    expect($user)->toBeInstanceOf(User::class);

    assertModelExists($user);

    expect($user->hasRole('Admin'))->toBeTrue()
        ->and(Hash::check('provided-password', $user->password))->toBeTrue()
        ->and(User::query()->where('email', 'admin@dev.com')->exists())->toBeFalse();
});

it('keeps the existing admin password when the seeder runs again', function (): void {
    config([
        'admin.email' => 'admin@dev.com',
        'admin.password' => 'first-password',
    ]);

    seed(DatabaseSeeder::class);

    config([
        'admin.password' => 'second-password',
    ]);

    seed(DatabaseSeeder::class);

    $user = User::query()->where('email', 'admin@dev.com')->sole();

    expect(Hash::check('first-password', $user->password))->toBeTrue()
        ->and(Hash::check('second-password', $user->password))->toBeFalse();
});

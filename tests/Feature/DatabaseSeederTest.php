<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\seed;

it('seeds a local admin account', function (): void {
    seed(DatabaseSeeder::class);

    $user = User::query()->where('email', 'admin@dev.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->hasRole('Admin'))->toBeTrue()
        ->and($user->hasVerifiedEmail())->toBeTrue()
        ->and(Hash::check('secret', $user->password))->toBeTrue();
});

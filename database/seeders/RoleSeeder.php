<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->firstOrCreate([
            'name' => 'Admin',
            'guard_name' => config('auth.defaults.guard'),
        ]);
    }
}

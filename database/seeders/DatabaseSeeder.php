<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\OutputInterface;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $email = $this->adminEmail();

        if (User::query()->where('email', $email)->exists()) {
            $this->writeExistingAdmin($email);

            return;
        }

        $password = $this->adminPassword();

        User::factory()->admin()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => $email,
            'password' => $password,
        ]);

        $this->writeAdminCredentials($email, $password);
    }

    private function adminEmail(): string
    {
        return $this->configuredString('admin.email') ?? 'admin@dev.com';
    }

    private function adminPassword(): string
    {
        return $this->configuredString('admin.password') ?? Str::password(16);
    }

    private function configuredString(string $key): ?string
    {
        $value = config($key);

        if (! is_string($value) || $value === '') {
            return null;
        }

        return $value;
    }

    private function writeAdminCredentials(string $email, string $password): void
    {
        if ($this->command === null) {
            return;
        }

        $this->command->newLine();
        $this->command->info('Local admin account');
        $this->writeRawLine('Email: '.$email);
        $this->writeRawLine('Password: '.$password);
    }

    private function writeExistingAdmin(string $email): void
    {
        if ($this->command === null) {
            return;
        }

        $this->command->newLine();
        $this->command->info('Local admin already exists.');
        $this->writeRawLine('Email: '.$email);
    }

    /**
     * Styled console output treats < and > as tags and would change the value a person copies.
     */
    private function writeRawLine(string $line): void
    {
        $this->command?->getOutput()->write($line.PHP_EOL, false, OutputInterface::OUTPUT_RAW);
    }
}

<?php

declare(strict_types=1);

test('composer update does not bump package constraints', function (): void {
    $composer = json_decode((string) file_get_contents(base_path('composer.json')), true);
    $postUpdate = $composer['scripts']['post-update-cmd'] ?? [];

    expect($postUpdate)
        ->not->toContain('@update:requirements')
        ->and(implode("\n", $postUpdate))
        ->not->toContain('composer bump')
        ->not->toContain('npm-check-updates');
});

test('dependency bumps stay on the dedicated composer script', function (): void {
    $composer = json_decode((string) file_get_contents(base_path('composer.json')), true);

    expect($composer['scripts']['update:requirements'] ?? [])
        ->toContain('composer bump')
        ->toContain('bunx npm-check-updates -u');
});

test('filament blueprint is not shipped in the composer files', function (): void {
    $composerJson = (string) file_get_contents(base_path('composer.json'));
    $composerLock = (string) file_get_contents(base_path('composer.lock'));

    expect($composerJson)
        ->not->toContain('filament/blueprint')
        ->not->toContain('packages.filamentphp.com')
        ->and($composerLock)
        ->not->toContain('filament/blueprint')
        ->not->toContain('packages.filamentphp.com');
});

test('a fresh project generates an app key, sqlite file, and migrations', function (): void {
    $composer = json_decode((string) file_get_contents(base_path('composer.json')), true);
    $postCreate = $composer['scripts']['post-create-project-cmd'] ?? [];
    $installerHooks = $composer['extra']['laravel']['installer']['post-create-project'] ?? [];
    $commands = implode("\n", $postCreate);

    expect($postCreate)->not->toBeEmpty()
        ->and($commands)->toContain('key:generate')
        ->and($commands)->toContain('database.sqlite')
        ->and($commands)->toContain('migrate')
        ->and($installerHooks)->toBe($postCreate);
});

test('boost update does not restore filament blueprint skills', function (): void {
    $boostConfig = (string) file_get_contents(base_path('config/boost.php'));
    $gitignore = (string) file_get_contents(base_path('.gitignore'));
    $boost = json_decode((string) file_get_contents(base_path('boost.json')), true);

    expect($boostConfig)->toContain("'planning-filament'")
        ->and($boostConfig)->toContain("'reviewing-filament-plans'")
        ->and($boostConfig)->toContain("'filament-security-audit'")
        ->and($gitignore)->toContain('skills/planning-filament/')
        ->and($gitignore)->toContain('skills/reviewing-filament-plans/')
        ->and($gitignore)->toContain('skills/filament-security-audit/')
        ->and($boost['packages'] ?? [])->not->toContain('filament/blueprint')
        ->and($boost['skills'] ?? [])->not->toContain('planning-filament')
        ->and($boost['skills'] ?? [])->not->toContain('reviewing-filament-plans')
        ->and($boost['skills'] ?? [])->not->toContain('filament-security-audit');
});

test('browser test suite directory exists', function (): void {
    expect(is_dir(base_path('tests/Browser')))->toBeTrue();
});

test('shared workflow callers pin main', function (): void {
    $files = glob(base_path('.github/workflows/*.yml'));

    expect($files)->not->toBeEmpty();

    foreach ($files as $file) {
        $contents = (string) file_get_contents($file);

        if (! str_contains($contents, 'TappNetwork/workflows/')) {
            continue;
        }

        expect($contents)
            ->toContain('@main')
            ->and($contents)->not->toContain('@feature/');
    }
});

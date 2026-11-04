<?php

declare(strict_types=1);

use App\Models\User;

$filamentPanelProviders = [
    'App\Providers\Filament',
];

$filamentCode = [
    'App\Filament',
    'App\Providers\Filament',
];

arch()->preset()->php();
arch()->preset()->strict()->ignoring([...$filamentCode, User::class]);
arch()->preset()->laravel()->ignoring($filamentPanelProviders);
arch()->preset()->security()->ignoring([
    'assert',
]);

arch('controllers')
    ->expect('App\Http\Controllers')
    ->not->toBeUsed();

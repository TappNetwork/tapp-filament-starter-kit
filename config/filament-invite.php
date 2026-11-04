<?php

declare(strict_types=1);

// config for Tapp/FilamentInvite
return [
    'expire' => 60 * 24 * 2, // 2 days

    'routes' => [
        'reset' => 'filament.app.auth.password-reset.reset',
    ],
];

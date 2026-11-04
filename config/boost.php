<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Excluded skills
    |--------------------------------------------------------------------------
    |
    | Filament Blueprint's skills ship with a license that forbids publishing
    | them. Keep them out of boost:update even on a machine that has the
    | package installed. Licensed developers opt in on their own app.
    |
    */

    'skills' => [
        'exclude' => [
            'filament-security-audit',
            'planning-filament',
            'reviewing-filament-plans',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cursor guidelines
    |--------------------------------------------------------------------------
    |
    | Boost writes Cursor guidelines to AGENTS.md unless this path is set.
    | Cursor also loads .cursor/rules, so keep the always-on project rule
    | on that path and let boost:install / boost:update refresh it.
    |
    */

    'agents' => [
        'cursor' => [
            'guidelines_path' => '.cursor/rules/laravel-boost.mdc',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Project rules
    |--------------------------------------------------------------------------
    |
    | This kit does not keep a .ai/rules store. Leave the feature off so
    | Boost stops telling agents to open .ai/rules/index.md. Tapp
    | conventions live in .ai/guidelines and are composed into the
    | agent instruction files.
    |
    */

    'rules' => [
        'enabled' => false,
    ],

];

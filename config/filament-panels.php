<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | App panel registration
    |--------------------------------------------------------------------------
    |
    | When disabled, the app panel will not serve a registration page and the
    | login screen will not link to one. Invites still work from the admin
    | panel. Email verification remains required for unverified users.
    |
    */

    'registration' => (bool) env('FILAMENT_REGISTRATION', true),

];

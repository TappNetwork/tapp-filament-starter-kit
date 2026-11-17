<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Local admin account
    |--------------------------------------------------------------------------
    |
    | DatabaseSeeder reads these values. Leave the password empty to generate
    | one. The seeder prints the email and password in the terminal when it
    | creates the account.
    |
    */

    'email' => env('ADMIN_EMAIL'),

    'password' => env('ADMIN_PASSWORD'),

];

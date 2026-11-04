<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

arch('database code uses strict types')
    ->expect('Database')
    ->toUseStrictTypes();

arch('database code is cased correctly')
    ->expect('Database')
    ->toBeCasedCorrectly();

arch('factories follow laravel conventions')
    ->expect('Database\Factories')
    ->classes()
    ->toHaveSuffix('Factory')
    ->toExtend(Factory::class)
    ->toHaveMethod('definition');

arch('seeders follow laravel conventions')
    ->expect('Database\Seeders')
    ->classes()
    ->toHaveSuffix('Seeder')
    ->toExtend(Seeder::class)
    ->toHaveMethod('run');

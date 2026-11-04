<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

arch('application uses strict types')
    ->expect('App')
    ->toUseStrictTypes();

arch('application classes are final')
    ->expect('App')
    ->classes()
    ->toBeFinal();

arch('application is cased correctly')
    ->expect('App')
    ->toBeCasedCorrectly();

arch('application uses strict equality')
    ->expect('App')
    ->toUseStrictEquality();

arch('application does not have suspicious characters')
    ->expect('App')
    ->not->toHaveSuspiciousCharacters();

arch('no debugging functions')
    ->expect(['dd', 'ddd', 'dump', 'var_dump', 'die', 'exit', 'ray'])
    ->not->toBeUsed();

arch('env is not used outside config files')
    ->expect('env')
    ->not->toBeUsedIn(['App', 'Database']);

arch('eloquent is preferred over the db facade')
    ->expect(DB::class)
    ->not->toBeUsedIn(['App', 'Database']);

arch('tests are not used in production code')
    ->expect('Tests')
    ->not->toBeUsedIn(['App', 'Database']);

<?php

declare(strict_types=1);

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

arch('actions are single-purpose classes')
    ->expect('App\Actions')
    ->classes()
    ->toHaveSuffix('Action')
    ->toBeFinal()
    ->toBeReadonly()
    ->toHaveMethod('handle');

arch('jobs are queued and suffixed')
    ->expect('App\Jobs')
    ->classes()
    ->toHaveSuffix('Job')
    ->toImplement(ShouldQueue::class);

arch('services do not depend on the http layer')
    ->expect('App\Services')
    ->not->toUse([
        Request::class,
        'App\Http\Controllers',
        'App\Http\Requests',
    ]);

arch('services use constructor injection')
    ->expect('App\Services')
    ->not->toUse([
        'Illuminate\Support\Facades',
        'auth',
        'session',
        'request',
        'app',
        'resolve',
    ]);

arch('query building lives in the data layer')
    ->expect([
        Builder::class,
        DB::class,
    ])
    ->toOnlyBeUsedIn([
        'App\Models',
        'App\Services',
        'App\Actions',
        'App\Jobs',
        'App\Console',
        'App\Filament',
        'Database',
    ]);

arch('enums are string backed')
    ->expect('App\Enums')
    ->toBeEnums()
    ->toBeStringBackedEnums()
    ->ignoring('App\Enums\Concerns');

arch('enum concerns are traits')
    ->expect('App\Enums\Concerns')
    ->toBeTraits();

arch('contracts are interfaces')
    ->expect('App\Contracts')
    ->toBeInterfaces();

arch('observers use the observer suffix')
    ->expect('App\Observers')
    ->classes()
    ->toHaveSuffix('Observer');

arch('validation rules use the rule suffix')
    ->expect('App\Rules')
    ->classes()
    ->toHaveSuffix('Rule');

arch('jobs handle failures')
    ->expect('App\Jobs')
    ->classes()
    ->toHaveMethod('failed');

arch('notifications are queued')
    ->expect('App\Notifications')
    ->classes()
    ->toImplement(ShouldQueue::class);

arch('concerns are traits')
    ->expect('App\*\Concerns')
    ->toBeTraits();

arch('value objects are immutable and dependency-free')
    ->expect('App\ValueObjects')
    ->classes()
    ->toBeFinal()
    ->toBeReadonly()
    ->toUseNothing();

<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

arch('models are eloquent models')
    ->expect('App\Models')
    ->classes()
    ->toExtend(Model::class)
    ->ignoring('App\Models\Scopes');

arch('models use factories')
    ->expect('App')
    ->extending(Model::class)
    ->toUseTrait(HasFactory::class);

arch('models do not reach outward')
    ->expect('App\Models')
    ->not->toUse([
        Request::class,
        Http::class,
        'App\Http',
        'App\Jobs',
        'App\Services',
        'App\Actions',
    ]);

arch('models define casts via the casts method')
    ->expect('App\Models')
    ->classes()
    ->toHaveMethod('casts')
    ->ignoring(['App\Models\Scopes', 'App\Models\Concerns']);

arch('model concerns are traits')
    ->expect('App\Models\Concerns')
    ->toBeTraits();

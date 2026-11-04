<?php

declare(strict_types=1);

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;

arch('form requests validate and authorize')
    ->expect('App\Http\Requests')
    ->classes()
    ->toExtend(FormRequest::class)
    ->toHaveSuffix('Request')
    ->toHaveMethod('rules')
    ->toHaveMethod('authorize');

arch('api resources are eloquent json resources')
    ->expect('App\Http\Resources')
    ->classes()
    ->toHaveSuffix('Resource')
    ->toExtend(JsonResource::class);

arch('http middleware has a handle method')
    ->expect('App\Http\Middleware')
    ->classes()
    ->toHaveMethod('handle');

arch('policies use the policy suffix')
    ->expect('App\Policies')
    ->classes()
    ->toHaveSuffix('Policy');

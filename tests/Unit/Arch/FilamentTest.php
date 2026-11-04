<?php

declare(strict_types=1);

use Filament\PanelProvider;
use Filament\Resources\Resource;
use Filament\Widgets\Widget;

arch('filament does not use legacy action or schema utility namespaces')
    ->expect('App')
    ->not->toUse([
        'Filament\Tables\Actions',
        'Filament\Forms\Actions',
        'Filament\Forms\Get',
        'Filament\Forms\Set',
    ]);

arch('filament concerns are traits')
    ->expect('App\Filament\Concerns')
    ->toBeTraits();

arch('filament resources use the resource suffix')
    ->expect('App\Filament')
    ->extending(Resource::class)
    ->toHaveSuffix('Resource');

arch('filament panel providers follow conventions')
    ->expect('App\Providers\Filament')
    ->classes()
    ->toHaveSuffix('Provider')
    ->toExtend(PanelProvider::class);

arch('filament resource tables use the table suffix')
    ->expect('App\Filament\Resources\*\Tables')
    ->classes()
    ->toHaveSuffix('Table');

arch('filament resource forms use the form suffix')
    ->expect('App\Filament\Resources\*\Schemas')
    ->classes()
    ->toHaveSuffix('Form');

arch('filament widgets extend widget')
    ->expect('App\Filament\Widgets')
    ->classes()
    ->toExtend(Widget::class);

arch('filament app panel widgets extend widget')
    ->expect('App\Filament\App\Widgets')
    ->classes()
    ->toExtend(Widget::class);

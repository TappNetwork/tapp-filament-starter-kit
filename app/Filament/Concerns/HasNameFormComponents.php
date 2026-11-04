<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Filament\Forms\Components\TextInput;

trait HasNameFormComponents
{
    protected function getFirstNameFormComponent(): TextInput
    {
        return TextInput::make('first_name')
            ->label(__('First name'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getLastNameFormComponent(): TextInput
    {
        return TextInput::make('last_name')
            ->label(__('Last name'))
            ->required()
            ->maxLength(255);
    }
}

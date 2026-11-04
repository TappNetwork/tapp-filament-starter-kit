<?php

declare(strict_types=1);

namespace App\Filament\App\Pages\Auth;

use App\Filament\Concerns\HasNameFormComponents;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;

final class Register extends BaseRegister
{
    use HasNameFormComponents;

    public function mount(): void
    {
        abort_unless((bool) config('filament-panels.registration'), 404);

        parent::mount();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}

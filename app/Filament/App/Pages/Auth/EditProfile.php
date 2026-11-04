<?php

declare(strict_types=1);

namespace App\Filament\App\Pages\Auth;

use App\Filament\Concerns\HasNameFormComponents;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Schema;

final class EditProfile extends BaseEditProfile
{
    use HasNameFormComponents;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}

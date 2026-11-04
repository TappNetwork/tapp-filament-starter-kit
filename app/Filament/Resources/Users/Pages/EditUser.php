<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use STS\FilamentImpersonate\Actions\Impersonate;
use Tapp\FilamentInvite\Actions\InviteAction;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            InviteAction::make()
                ->record($this->getRecord()),
            Impersonate::make()->record($this->getRecord()),
            DeleteAction::make(),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Http\Middleware;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;
use Illuminate\Http\Request;

final class RedirectToAppLogin extends FilamentAuthenticate
{
    /**
     * @param  Request  $request
     */
    protected function redirectTo($request): ?string
    {
        return Filament::getPanel('app')->getLoginUrl();
    }
}

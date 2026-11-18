<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Models\User;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Description('Returns the application name and how many users it has.')]
#[IsReadOnly]
final class ApplicationSummaryTool extends Tool
{
    public function handle(Request $request): Response
    {
        $name = config()->string('app.name');
        $users = User::query()->count();

        return Response::text('Application name: '.$name.'. Users: '.$users.'.');
    }
}

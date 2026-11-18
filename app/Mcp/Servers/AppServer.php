<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\ApplicationSummaryTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('App')]
#[Version('1.0.0')]
#[Instructions('Read-only information about this application.')]
final class AppServer extends Server
{
    /**
     * @var array<int, class-string<ApplicationSummaryTool>>
     */
    protected array $tools = [
        ApplicationSummaryTool::class,
    ];
}

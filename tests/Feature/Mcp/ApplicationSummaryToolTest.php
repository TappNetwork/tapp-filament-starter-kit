<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use App\Mcp\Tools\ApplicationSummaryTool;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

it('returns 401 when the app server has no bearer token', function (): void {
    postJson('/mcp/app')->assertUnauthorized();
});

it('returns the application summary when the token has the ability', function (): void {
    config(['app.name' => 'Tapp']);

    Sanctum::actingAs(User::factory()->create(), ['application-summary-tool']);

    AppServer::tool(ApplicationSummaryTool::class)
        ->assertOk()
        ->assertSee('Application name: Tapp. Users: 1.');
});

it('refuses the application summary when the token lacks the ability', function (): void {
    Sanctum::actingAs(User::factory()->create(), ['another-tool']);

    AppServer::tool(ApplicationSummaryTool::class)
        ->assertHasErrors(['Token lacks ability application-summary-tool.']);
});

it('refuses the application summary when the signed-in user has no token', function (): void {
    actingAs(User::factory()->create());

    AppServer::tool(ApplicationSummaryTool::class)
        ->assertHasErrors(['Token lacks ability application-summary-tool.']);
});

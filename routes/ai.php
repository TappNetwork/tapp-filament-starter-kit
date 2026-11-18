<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/app', AppServer::class)
    ->middleware('auth:sanctum');

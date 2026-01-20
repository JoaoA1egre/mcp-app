<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\StarWarsTool;
use Laravel\Mcp\Server;

class StarWarsServer extends Server
{
    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        StarWarsTool::class,
    ];

    protected string $instructions = 'Este servidor fornece acesso a dados da franquia Star Wars via API e banco local.';
}
<?php

declare(strict_types=1);

use App\Mcp\Servers\StarWarsServer;
use Laravel\Mcp\Facades\Mcp;

// O primeiro argumento é o "handle" (nome) e o segundo é a classe do servidor
Mcp::local('star-wars-explorer', StarWarsServer::class);
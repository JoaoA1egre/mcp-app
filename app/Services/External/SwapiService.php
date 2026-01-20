<?php

declare(strict_types=1);

namespace App\Services\External;

use Illuminate\Support\Facades\Http;

final readonly class SwapiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://swapi.bry.com.br/api';
    }

    /** @return array<string, mixed> */
    public function fetchPerson(int $id): array
    {
        return Http::get("{$this->baseUrl}/people/{$id}/")->json() ?? [];
    }
}
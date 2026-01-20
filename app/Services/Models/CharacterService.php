<?php

declare(strict_types=1);

namespace App\Services\Models;

use App\Models\Character;
use App\Services\External\SwapiService;

final readonly class CharacterService
{
    public function __construct(
        private SwapiService $swapiService
    ) {}

    public function syncCharacter(int $id): ?Character
    {
        $data = $this->swapiService->fetchPerson($id);

        if (empty($data) || isset($data['detail'])) {
            return null;
        }

        return Character::updateOrCreate(
            ['name' => $data['name']],
            [
                'height' => $data['height'] ?? 'unknown',
                'mass' => $data['mass'] ?? 'unknown',
                'hair_color' => $data['hair_color'] ?? 'unknown',
                'birth_year' => $data['birth_year'] ?? 'unknown',
            ]
        );
    }
}
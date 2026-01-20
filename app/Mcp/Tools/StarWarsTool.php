<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Services\Models\CharacterService;
use App\Models\Character;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

final class StarWarsTool extends Tool
{
    protected string $description = 'Busca ou sincroniza personagens de Star Wars via ID (API) ou Nome (Local).';

    public function __construct(
        private CharacterService $characterService
    ) {}

    public function handle(Request $request): Response
    {
        $id = $request->get('id');
        $search = $request->get('search');

        if ($id !== null) {
            $character = $this->characterService->syncCharacter((int) $id);
            
            if ($character === null) {
                return Response::text('Personagem não encontrado na API.');
            }

            return Response::structured([
                'character' => [
                    'id' => $character->id,
                    'name' => $character->name,
                    'height' => $character->height,
                    'mass' => $character->mass,
                    'hair_color' => $character->hair_color,
                    'birth_year' => $character->birth_year,
                ],
            ]);
        }

        if ($search !== null) {
            $characters = Character::where('name', 'like', "%{$search}%")->get();
            
            return Response::structured([
                'characters' => $characters->map(fn (Character $character): array => [
                    'id' => $character->id,
                    'name' => $character->name,
                    'height' => $character->height,
                    'mass' => $character->mass,
                    'hair_color' => $character->hair_color,
                    'birth_year' => $character->birth_year,
                ])->toArray(),
            ]);
        }

        $characters = Character::all();
        
        return Response::structured([
            'characters' => $characters->map(fn (Character $character): array => [
                'id' => $character->id,
                'name' => $character->name,
                'height' => $character->height,
                'mass' => $character->mass,
                'hair_color' => $character->hair_color,
                'birth_year' => $character->birth_year,
            ])->toArray(),
        ]);
    }

    /**
     * @return array<string, \Illuminate\Contracts\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('ID do personagem para importar da API externa')
                ->nullable(),
            'search' => $schema->string()
                ->description('Nome do personagem para buscar no banco de dados local')
                ->nullable(),
        ];
    }
}
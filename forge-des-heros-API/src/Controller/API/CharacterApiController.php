<?php

namespace App\Controller\API;

use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CharacterApiController extends AbstractController
{
    private static function normalizeParties(iterable $parties): array
    {
        $normalized = [];

        foreach ($parties as $party) {
            $normalized[] = [
                'id' => $party->getId(),
                'name' => $party->getName(),
                'description' => $party->getDescription(),
                'maxSize' => $party->getMaxSize(),
                'currentSize' => $party->getCharacters()->count(),
            ];
        }

        return $normalized;
    }

    #[Route('/api/v1/characters', methods: ['GET'])]
    public function index(Request $request, CharacterRepository $repo): JsonResponse
    {
        $name = $request->query->get('name');
        $classId = $request->query->getInt('class', 0);
        $raceId = $request->query->getInt('race', 0);

        $characters = $repo->findForApi(
            is_string($name) ? $name : null,
            $classId > 0 ? $classId : null,
            $raceId > 0 ? $raceId : null
        );

        $data = array_map(static fn ($character) => [
            'id' => $character->getId(),
            'name' => $character->getName(),
            'level' => $character->getLevel(),
            'class' => $character->getCharacterClass() ? [
                'id' => $character->getCharacterClass()->getId(),
                'name' => $character->getCharacterClass()->getName(),
            ] : null,
            'race' => $character->getRace() ? [
                'id' => $character->getRace()->getId(),
                'name' => $character->getRace()->getName(),
            ] : null,
            'parties' => self::normalizeParties($character->getParties()),
        ], $characters);

        return $this->json([
            'data' => $data,
            'meta' => [
                'filters' => [
                    'name' => is_string($name) ? $name : null,
                    'class' => $classId > 0 ? $classId : null,
                    'race' => $raceId > 0 ? $raceId : null,
                ],
            ],
        ]);
    }

    #[Route('/api/v1/characters/{id<\d+>}', methods: ['GET'])]
    public function show(int $id, CharacterRepository $repo): JsonResponse
    {
        $character = $repo->find($id);
        if (!$character) {
            return $this->json(['error' => 'Character not found'], 404);
        }

        return $this->json([
            'data' => [
                'id' => $character->getId(),
                'name' => $character->getName(),
                'level' => $character->getLevel(),
                'stats' => [
                    'strength' => $character->getStrength(),
                    'dexterity' => $character->getDexterity(),
                    'constitution' => $character->getConstitution(),
                    'intelligence' => $character->getIntelligence(),
                    'wisdom' => $character->getWisdom(),
                    'charisma' => $character->getCharisma(),
                    'healthPoints' => $character->getHealthPoints(),
                ],
                'image' => $character->getImage(),
                'class' => $character->getCharacterClass() ? [
                    'id' => $character->getCharacterClass()->getId(),
                    'name' => $character->getCharacterClass()->getName(),
                ] : null,
                'race' => $character->getRace() ? [
                    'id' => $character->getRace()->getId(),
                    'name' => $character->getRace()->getName(),
                ] : null,
                'parties' => self::normalizeParties($character->getParties()),
            ],
        ]);
    }
}

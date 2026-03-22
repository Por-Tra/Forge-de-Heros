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
                'id'          => $party->getId(),
                'name'        => $party->getName(),
                'description' => $party->getDescription(),
                'maxSize'     => $party->getMaxSize(),
                'currentSize' => $party->getCharacters()->count(),
            ];
        }
        return $normalized;
    }

    private static function normalizeClass(?object $class): ?array
    {
        if (!$class) return null;
        return [
            'id'          => $class->getId(),
            'name'        => $class->getName(),
            'description' => $class->getDescription(),
            'healthDice'  => $class->getHealthDice(),
        ];
    }

    private static function normalizeRace(?object $race): ?array
    {
        if (!$race) return null;
        return [
            'id'          => $race->getId(),
            'name'        => $race->getName(),
            'description' => $race->getDescription(),
        ];
    }

    #[Route('/api/v1/characters', methods: ['GET'])]
    public function index(Request $request, CharacterRepository $repo): JsonResponse
    {
        $name    = $request->query->get('name');
        $classId = $request->query->getInt('class', 0);
        $raceId  = $request->query->getInt('race', 0);

        $characters = $repo->findForApi(
            is_string($name) ? $name : null,
            $classId > 0 ? $classId : null,
            $raceId  > 0 ? $raceId  : null,
        );

        $data = array_map(fn($c) => [
            'id'           => $c->getId(),
            'name'         => $c->getName(),
            'level'        => $c->getLevel(),
            'healthPoints' => $c->getHealthPoints(),
            'image'        => $c->getImage(),
            'class'        => self::normalizeClass($c->getCharacterClass()),
            'race'         => self::normalizeRace($c->getRace()),
            // Ajout pour le front
            'stats' => [
                'strength'     => $c->getStrength(),
                'dexterity'    => $c->getDexterity(),
                'constitution' => $c->getConstitution(),
                'intelligence' => $c->getIntelligence(),
                'wisdom'       => $c->getWisdom(),
                'charisma'     => $c->getCharisma(),
            ],
            'skills' => $c->getCharacterClass()
                ? array_map(fn($s) => [
                    'id'      => $s->getId(),
                    'name'    => $s->getName(),
                    'ability' => $s->getAbility(),
                ], $c->getCharacterClass()->getSkills()->toArray())
                : [],
            'parties' => self::normalizeParties($c->getParties()),
        ], $characters);

        return $this->json([
            'data' => $data,
            'meta' => [
                'total'   => count($data),
                'filters' => [
                    'name'  => is_string($name) ? $name : null,
                    'class' => $classId > 0 ? $classId : null,
                    'race'  => $raceId  > 0 ? $raceId  : null,
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

        // Compétences issues de la classe du personnage
        $skills = [];
        if ($character->getCharacterClass()) {
            foreach ($character->getCharacterClass()->getSkills() as $skill) {
                $skills[] = [
                    'id'      => $skill->getId(),
                    'name'    => $skill->getName(),
                    'ability' => $skill->getAbility(),
                ];
            }
        }

        return $this->json([
            'data' => [
                'id'           => $character->getId(),
                'name'         => $character->getName(),
                'level'        => $character->getLevel(),
                'healthPoints' => $character->getHealthPoints(),
                'image'        => $character->getImage(),
                'stats'        => [
                    'strength'     => $character->getStrength(),
                    'dexterity'    => $character->getDexterity(),
                    'constitution' => $character->getConstitution(),
                    'intelligence' => $character->getIntelligence(),
                    'wisdom'       => $character->getWisdom(),
                    'charisma'     => $character->getCharisma(),
                ],
                'class'        => self::normalizeClass($character->getCharacterClass()),
                'race'         => self::normalizeRace($character->getRace()),
                'skills'       => $skills,
                'parties'      => self::normalizeParties($character->getParties()),
            ],
        ]);
    }
}
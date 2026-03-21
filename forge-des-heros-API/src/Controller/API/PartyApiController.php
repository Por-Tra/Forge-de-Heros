<?php

namespace App\Controller\API;

use App\Repository\PartyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PartyApiController extends AbstractController
{
    #[Route('/api/v1/parties', methods: ['GET'])]
    public function index(Request $request, PartyRepository $repo): JsonResponse
    {
        $status = (string) $request->query->get('status', 'all');
        if (!in_array($status, ['all', 'full', 'available'], true)) {
            $status = 'all';
        }

        $parties = $repo->findByCapacityStatus($status);

        $data = array_map(static function ($party): array {
            $maxSize = $party->getMaxSize();
            $currentSize = $party->getCharacters()->count();

            return [
                'id' => $party->getId(),
                'name' => $party->getName(),
                'description' => $party->getDescription(),
                'maxSize' => $maxSize,
                'currentSize' => $currentSize,
                'isFull' => null !== $maxSize && $currentSize >= $maxSize,
            ];
        }, $parties);

        return $this->json([
            'data' => $data,
            'meta' => [
                'filters' => ['status' => $status],
            ],
        ]);
    }

    #[Route('/api/v1/parties/{id<\d+>}', methods: ['GET'])]
    public function show(int $id, PartyRepository $repo): JsonResponse
    {
        $party = $repo->find($id);
        if (!$party) {
            return $this->json(['error' => 'Party not found'], 404);
        }

        $members = array_map(static fn ($character) => [
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
        ], $party->getCharacters()->toArray());

        $maxSize = $party->getMaxSize();
        $currentSize = $party->getCharacters()->count();

        return $this->json([
            'data' => [
                'id' => $party->getId(),
                'name' => $party->getName(),
                'description' => $party->getDescription(),
                'maxSize' => $maxSize,
                'currentSize' => $currentSize,
                'isFull' => null !== $maxSize && $currentSize >= $maxSize,
                'members' => $members,
            ],
        ]);
    }
}

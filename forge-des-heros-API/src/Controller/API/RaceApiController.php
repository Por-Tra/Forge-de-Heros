<?php

namespace App\Controller\API;

use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class RaceApiController extends AbstractController
{
    #[Route('/api/v1/races', methods: ['GET'])]
    public function index(RaceRepository $repo): JsonResponse
    {
        $races = $repo->findAll();

        $data = array_map(static fn ($race) => [
            'id' => $race->getId(),
            'name' => $race->getName(),
            'description' => $race->getDescription(),
        ], $races);

        return $this->json(['data' => $data]);
    }

    #[Route('/api/v1/races/{id<\d+>}', methods: ['GET'])]
    public function show(int $id, RaceRepository $repo): JsonResponse
    {
        $race = $repo->find($id);
        if (!$race) {
            return $this->json(['error' => 'Race not found'], 404);
        }

        return $this->json([
            'data' => [
                'id' => $race->getId(),
                'name' => $race->getName(),
                'description' => $race->getDescription(),
            ],
        ]);
    }
}

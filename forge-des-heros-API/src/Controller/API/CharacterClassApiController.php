<?php

namespace App\Controller\API;

use App\Repository\CharacterClassRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class CharacterClassApiController extends AbstractController
{
    #[Route('/api/v1/classes', methods: ['GET'])]
    public function index(CharacterClassRepository $repo): JsonResponse
    {
        $classes = $repo->findAll();

        $data = array_map(static fn ($class) => [
            'id' => $class->getId(),
            'name' => $class->getName(),
            'description' => $class->getDescription(),
            'healthDice' => $class->getHealthDice(),
        ], $classes);

        return $this->json(['data' => $data]);
    }

    #[Route('/api/v1/classes/{id<\d+>}', methods: ['GET'])]
    public function show(int $id, CharacterClassRepository $repo): JsonResponse
    {
        $class = $repo->find($id);
        if (!$class) {
            return $this->json(['error' => 'Class not found'], 404);
        }

        $skills = array_map(static fn ($skill) => [
            'id' => $skill->getId(),
            'name' => $skill->getName(),
            'ability' => $skill->getAbility(),
        ], $class->getSkills()->toArray());

        return $this->json([
            'data' => [
                'id' => $class->getId(),
                'name' => $class->getName(),
                'description' => $class->getDescription(),
                'healthDice' => $class->getHealthDice(),
                'skills' => $skills,
            ],
        ]);
    }
}

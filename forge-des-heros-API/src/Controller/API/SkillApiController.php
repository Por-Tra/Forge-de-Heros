<?php

namespace App\Controller\API;

use App\Repository\SkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SkillApiController extends AbstractController
{
    #[Route('/api/v1/skills', methods: ['GET'])]
    public function index(SkillRepository $repo): JsonResponse
    {
        $skills = $repo->findAll();

        $data = array_map(static fn ($skill) => [
            'id' => $skill->getId(),
            'name' => $skill->getName(),
            'ability' => $skill->getAbility(),
        ], $skills);

        return $this->json(['data' => $data]);
    }
}

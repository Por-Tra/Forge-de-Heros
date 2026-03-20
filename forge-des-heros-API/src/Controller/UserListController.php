<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[Route('/user')]
#[IsGranted('ROLE_ADMIN')]
final class UserListController extends AbstractController
{

    #[Route(name: 'user_list', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('User-list/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccessErrorController extends AbstractController
{
    #[Route('/access-denied', name: 'app_access_denied', methods: ['GET'])]
    public function accessDenied(): Response
    {
        return $this->render('error/access_denied.html.twig');
    }
}

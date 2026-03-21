<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Party;
use App\Form\PartyType;
use App\Repository\CharacterRepository;
use App\Repository\PartyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/party')]
#[isGranted("ROLE_USER")]
final class PartyController extends AbstractController
{
    #[Route(name: 'app_party_index', methods: ['GET'])]
    public function index(PartyRepository $partyRepository): Response
    {
        return $this->render('party/index.html.twig', [
            'parties' => $partyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_party_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $party = new Party();
        $form = $this->createForm(PartyType::class, $party);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $party->setCreator($this->getUser());
            $entityManager->persist($party);
            $entityManager->flush();

            return $this->redirectToRoute('app_party_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('party/new.html.twig', [
            'party' => $party,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_party_show', methods: ['GET'])]
    public function show(Party $party, CharacterRepository $characterRepository): Response
    {
        $user = $this->getUser();
        $joinableCharacters = [];
        $leavableCharacters = [];
        $maxSize = $party->getMaxSize();
        $partyIsFull = null !== $maxSize && $party->getCharacters()->count() >= $maxSize;

        if ($user && method_exists($user, 'getId') && null !== $user->getId()) {
            $userCharacters = $characterRepository->findBy(['user' => $user]);
            foreach ($userCharacters as $character) {
                if ($party->getCharacters()->contains($character)) {
                    $leavableCharacters[] = $character;
                } else {
                    $joinableCharacters[] = $character;
                }
            }
        }

        return $this->render('party/show.html.twig', [
            'party' => $party,
            'partyIsFull' => $partyIsFull,
            'joinableCharacters' => $joinableCharacters,
            'leavableCharacters' => $leavableCharacters,
        ]);
    }

    #[Route('/{id}/join/{characterId}', name: 'app_party_join', methods: ['POST'])]
    public function join(
        Request $request,
        Party $party,
        int $characterId,
        CharacterRepository $characterRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $character = $characterRepository->find($characterId);

        if (!$character instanceof Character) {
            $this->addFlash('error', 'Personnage introuvable.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        if (!$this->isCsrfTokenValid('party_join_'.$party->getId().'_'.$character->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Jeton de securite invalide.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        $currentUser = $this->getUser();
        if (!$currentUser || !method_exists($currentUser, 'getId') || $character->getUser()?->getId() !== $currentUser->getId()) {
            $this->addFlash('error', 'Ce personnage ne vous appartient pas.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        if ($party->getCharacters()->contains($character)) {
            $this->addFlash('error', 'Ce personnage est deja dans ce groupe.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        $maxSize = $party->getMaxSize();
        if (null !== $maxSize && $party->getCharacters()->count() >= $maxSize) {
            $this->addFlash('error', 'Le groupe est complet.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        $party->addCharacter($character);
        $entityManager->flush();
        $this->addFlash('success', 'Personnage inscrit au groupe.');

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    #[Route('/{id}/leave/{characterId}', name: 'app_party_leave', methods: ['POST'])]
    public function leave(
        Request $request,
        Party $party,
        int $characterId,
        CharacterRepository $characterRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $character = $characterRepository->find($characterId);

        if (!$character instanceof Character) {
            $this->addFlash('error', 'Personnage introuvable.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        if (!$this->isCsrfTokenValid('party_leave_'.$party->getId().'_'.$character->getId(), $request->getPayload()->getString('_token'))) {
            $this->addFlash('error', 'Jeton de securite invalide.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        $currentUser = $this->getUser();
        if (!$currentUser || !method_exists($currentUser, 'getId') || $character->getUser()?->getId() !== $currentUser->getId()) {
            $this->addFlash('error', 'Ce personnage ne vous appartient pas.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        if (!$party->getCharacters()->contains($character)) {
            $this->addFlash('error', 'Ce personnage n\'est pas dans ce groupe.');

            return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
        }

        $party->removeCharacter($character);
        $entityManager->flush();
        $this->addFlash('success', 'Personnage retire du groupe.');

        return $this->redirectToRoute('app_party_show', ['id' => $party->getId()]);
    }

    #[Route('/{id}/edit', name: 'app_party_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Party $party, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartyType::class, $party);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_party_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('party/edit.html.twig', [
            'party' => $party,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_party_delete', methods: ['POST'])]
    public function delete(Request $request, Party $party, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$party->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($party);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_party_index', [], Response::HTTP_SEE_OTHER);
    }
}

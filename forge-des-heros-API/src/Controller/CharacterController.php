<?php

namespace App\Controller;

use App\Entity\Character;
use App\Form\CharacterType;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\CharacterClassRepository;
use App\Repository\RaceRepository;

#[Route('/character')]
#[IsGranted('ROLE_USER')]
final class CharacterController extends AbstractController
{
    #[Route(name: 'app_character_index', methods: ['GET'])]
    public function index(Request $request, CharacterRepository $characterRepository, CharacterClassRepository $characterClassRepository, RaceRepository $raceRepository): Response 
    {
        // Récupération des paramètres de recherche et de filtrage
        $search  = $request->query->get('search');
        $classId = $request->query->get('class');
        $raceId  = $request->query->get('race');

        // Filter les perso en fonction des paramètre -> voir CharacterRepository.findWithFilters()
        $characters = $characterRepository->findWithFilters($search, $classId, $raceId);

        return $this->render('character/index.html.twig', [
            'characters' => $characters, 'classes'=> $characterClassRepository->findAll(), 'races'=> $raceRepository->findAll()]);
    }

    // Ajouter un nouveaux personnage
    #[Route('/new', name: 'app_character_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%avatars_directory%')] string $avatarsDirectory,
    ): Response
    {
        $character = new Character();
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // The created character must belong to the currently authenticated user.
            $character->setUser($this->getUser());

            // TODO : Calculer les PV automatiquement ICI
            //! PV = maximum du dé de vie + modificateur de Constitution

            /** @var UploadedFile|null $avatarFile */
            $avatarFile = $form->get('image')->getData();

            // Mettre un avatar
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);

                //& Utiliser l'extension du client pour éviter la dépendance à fileinfo (MIME guessing).
                $extension = strtolower($avatarFile->getClientOriginalExtension());
                // 
                if (!in_array($extension, ['png', 'jpg', 'jpeg', 'webp'], true)) //& Vérif l'extension
                {
                    $this->addFlash('error', 'Format d\'avatar non supporte.');

                    return $this->render('character/new.html.twig', [
                        'character' => $character,
                        'form' => $form,
                    ]);
                }

                // Build a unique filename to prevent collisions.
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$extension;

                try 
                {
                    $avatarFile->move($avatarsDirectory, $newFilename);
                    $character->setImage($newFilename);
                } catch (FileException) 
                {
                    $this->addFlash('error', 'Impossible de televerser l\'avatar.');
                }
            }

            $entityManager->persist($character);
            $entityManager->flush();

            return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('character/new.html.twig', ['character' => $character, 'form' => $form,]);
    }

    #[Route('/{id}', name: 'app_character_show', methods: ['GET'])]
    public function show(Character $character): Response
    {
        return $this->render('character/show.html.twig', [
            'character' => $character,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_character_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Character $character,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%avatars_directory%')] string $avatarsDirectory,
    ): Response
    {
        $form = $this->createForm(CharacterType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $avatarFile */
            $avatarFile = $form->get('image')->getData();

            if ($avatarFile) {
                $oldAvatar = $character->getImage();
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                // Use the client extension to avoid MIME guessing dependency (fileinfo).
                $extension = strtolower($avatarFile->getClientOriginalExtension());
                // Keep a strict whitelist for allowed avatar formats.
                if (!in_array($extension, ['png', 'jpg', 'jpeg', 'webp'], true)) {
                    $this->addFlash('error', 'Format d\'avatar non supporte.');

                    return $this->render('character/edit.html.twig', [
                        'character' => $character,
                        'form' => $form,
                    ]);
                }

                // Build a unique filename to prevent collisions.
                $newFilename = $safeFilename.'-'.uniqid('', true).'.'.$extension;

                try {
                    $avatarFile->move($avatarsDirectory, $newFilename);
                    $character->setImage($newFilename);

                    if ($oldAvatar) {
                        $oldAvatarPath = $avatarsDirectory.DIRECTORY_SEPARATOR.$oldAvatar;
                        if (is_file($oldAvatarPath)) {
                            @unlink($oldAvatarPath);
                        }
                    }
                } catch (FileException) {
                    $this->addFlash('error', 'Impossible de televerser le nouvel avatar.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('character/edit.html.twig', [
            'character' => $character,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_character_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Character $character,
        EntityManagerInterface $entityManager,
        #[Autowire('%avatars_directory%')] string $avatarsDirectory,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$character->getId(), $request->getPayload()->getString('_token'))) {
            $avatar = $character->getImage();
            if ($avatar) {
                $avatarPath = $avatarsDirectory.DIRECTORY_SEPARATOR.$avatar;
                if (is_file($avatarPath)) {
                    @unlink($avatarPath);
                }
            }

            $entityManager->remove($character);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_character_index', [], Response::HTTP_SEE_OTHER);
    }


    //fonction pour la recherche de personnages par nom
    public function search(Request $request, CharacterRepository $characterRepository): Response
    {
        $query = $request->query->get('search', '');
        $characters = [];

        if ($query) {
            $characters = $characterRepository->searchByName($query);
        }

        return $this->render('character/search.html.twig', [
            'characters' => $characters,
            'query' => $query,
        ]);
    }


}

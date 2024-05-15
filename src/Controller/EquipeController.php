<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/equipe')]
class EquipeController extends AbstractController
{
    #[Route('/', name: 'app_equipe_index', methods: ['GET', 'POST'])]
    public function index(EquipeRepository $equipeRepository): Response
    {

        return $this->render('equipe/index.html.twig', [
            'equipes' => $equipeRepository->findAll(),
           
            
        ]);
    }

    #[Route('/new', name: 'app_equipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $equipe = new Equipe();
        $form = $this->createForm(EquipeType::class, $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $originalExtension = $file->guessExtension();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $originalExtension;
                $file->move(
                    $this->getParameter('upload_directory'), // Make sure this parameter is defined in config/parameters.yaml
                    $newFilename
                );
            }
            $equipe->setImage($newFilename);
            $entityManager->persist($equipe);
            $entityManager->flush();
            $this->addFlash('success', 'Equipe ajoutée avec succès! ');

            return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('equipe/new.html.twig', [
            'equipe' => $equipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_equipe_show', methods: ['GET'])]
    public function show(int $id, EquipeRepository $equipeRepository): Response
    {
        $equipe = $equipeRepository->find($id);
    
        if (!$equipe) {
            throw $this->createNotFoundException();
        }
    
        return $this->render('equipe/show.html.twig', [
            'equipe' => $equipe,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_equipe_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, EquipeRepository $equipeRepository, int $id, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
{
    $equipe = $equipeRepository->find($id);
    if (!$equipe) {
        throw $this->createNotFoundException('Equipe not found');
    }

    $form = $this->createForm(EquipeType::class, $equipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('image')->getData();
        if ($file) {
            $originalExtension = $file->guessExtension();
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $originalExtension;
            $file->move(
                $this->getParameter('upload_directory'),
                $newFilename
            );
            $equipe->setImage($newFilename);
        }
        $entityManager->flush();
        $this->addFlash('success', 'Equipe modifiée avec succès! ');

        return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('equipe/edit.html.twig', [
        'equipe' => $equipe,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_equipe_delete', methods: ['POST','GET'], requirements: ['id' => '\d+'])]
public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
{
    $equipe = $entityManager->getRepository(Equipe::class)->find($id);

    if (!$equipe) {
        throw $this->createNotFoundException();
    }

    if ($this->isCsrfTokenValid('delete' . $equipe->getId(), $request->request->get('_token'))) {
        $entityManager->remove($equipe);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_equipe_index', [], Response::HTTP_SEE_OTHER);
}

#[Route('/equipes', name: 'equipes', methods: ['GET'])]
public function indexfront(EquipeRepository $equipeRepository): Response
{
    $equipes = $equipeRepository->findAll();

    return $this->render('equipe/indexfront.html.twig', [
        'equipes' => $equipes,
    ]);
}

#[Route('/{id}/players', name: 'app_equipe_show_players')]
public function showPlayers(int $id, EquipeRepository $equipeRepository, JoueurRepository $joueurRepository): Response
{
    $equipe = $equipeRepository->find($id);
    $joueurs = $joueurRepository->findByEquipeId($id);

    return $this->render('equipe/player_list.html.twig', [
        'equipe' => $equipe,
        'joueurs' => $joueurs,
    ]);
}


}

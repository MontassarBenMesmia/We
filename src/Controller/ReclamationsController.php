<?php

namespace App\Controller;

use App\Entity\Reclamations;
use App\Form\ReclamationsType;
use App\Repository\ReclamationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationsController extends AbstractController
{
    /**
     * @Route("/reclamations", name="reclamations")
     */
    public function index(ReclamationsRepository $reclamationsRepository): Response
    {
        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/reclamations/new", name="new_reclamations")
     */
    public function new(Request $request, ReclamationsRepository $reclamationsRepository): Response
    {
        $reclamation = new Reclamations();
        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat('En cours');
            $reclamationsRepository->add($reclamation);
            $this->addFlash('success', 'Reclamation created successfully');
            return $this->redirectToRoute('reclamations');
        }

        return $this->render('reclamations/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reclamations/{id}", name="show_reclamations")
     */
    public function show($id, ReclamationsRepository $reclamationsRepository): Response
    {
        $reclamation = $reclamationsRepository->find($id);
        // Check if reclamation exists, handle if not found

        return $this->render('reclamations/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/reclamations/{id}/edit", name="edit_reclamations")
     */
    public function edit(Request $request, $id, ReclamationsRepository $reclamationsRepository): Response
    {
        $reclamation = $reclamationsRepository->find($id);
        // Check if reclamation exists, handle if not found

        $form = $this->createForm(ReclamationsType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat('En cours');
            $reclamationsRepository->update($reclamation);
            $this->addFlash('success', 'Reclamation updated successfully');
            return $this->redirectToRoute('reclamations');
        }

        return $this->render('reclamations/edit.html.twig', [
            'form' => $form->createView(),
        ]);
        
    }

    /**
     * @Route("/reclamations/{id}/delete", name="delete_reclamations")
     */
    public function delete(Reclamations $reclamation, ReclamationsRepository $reclamationsRepository): Response
{
    $reclamation = $reclamationsRepository->find($id);
    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }
    $reclamationsRepository->delete($reclamation);
    $this->addFlash('success', 'Reclamation deleted successfully');
    return $this->redirectToRoute('reclamations');
}

    /**
     * @Route("/reclamations/search", name="search_reclamations")
     */
    public function search(Request $request, ReclamationsRepository $reclamationsRepository): Response
    {
        $searchTerm = $request->query->get('search');
        $reclamations = $reclamationsRepository->searchReclamations($searchTerm);

        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }
}
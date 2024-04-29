<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseController extends AbstractController
{
    /**
     * @Route("/reponse/new", name="reponse_new")
     */
    public function new(Request $request): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setEtat('Traite');
            $reclamationsRepository->addR($reponse);
            $this->addFlash('success', 'Response created successfully');
            return $this->redirectToRoute('reclamations');
        }

        return $this->render('reponse/new.html.twig', [
            'form' => $form->createView(),
            'reclamation'=> $reclamation,
        ]);
    }
}

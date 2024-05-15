<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationsRepository;


class NewIndexController extends AbstractController
{
    /**
     * @Route("/new_index", name="new_index")
     */
    public function index(ReclamationsRepository $reclamationsRepository): Response
    {
        $reclamations = $reclamationsRepository->findBy(['email' => 'montassarbenmesmia@gmail.com']);

        return $this->render('reclamations/index1.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

}
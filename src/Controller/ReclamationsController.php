<?php

namespace App\Controller;

use App\Entity\Reclamations;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Form\ReclamationsType;
use App\Repository\ReclamationsRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;



class ReclamationsController extends AbstractController
{
    /**
     * @Route("/reclamations", name="reclamations")
     */
    public function index(ReclamationsRepository $reclamationsRepository): Response
    {
        $reclamations = $reclamationsRepository->findAll();
    
        if (!$reclamations) {
            $reclamations = [];
        }
    
        return $this->render('reclamations/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    private $mailer;

public function __construct(MailerInterface $mailer)
{
    $this->mailer = $mailer;
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
    
            // Send an email using the Symfony Mailer component
            $email = (new Email())
                ->from('montassarbenmesmia@outlook.com')
                ->to('montassarbenmesmia@gmail.com')
                ->subject('New Reclamation')
                ->text('This is a new reclamation from the website.')
                ->html('<p>This is a new reclamation from the website.</p>');
    
            $this->mailer->send($email);
    
            $this->addFlash('success', 'Reclamation created successfully');
            return $this->redirectToRoute('new_index');
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
            $reclamation->setTypeReclamation($form->get('typeReclamation')->getData());
            $reclamationsRepository->update($reclamation);
            $this->addFlash('success', 'Reclamation updated successfully');
            return $this->redirectToRoute('new_index');
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
 * @Route("/reclamations/search", name="reclamations_search")
 */

 public function searchReclamations(Request $request, ReclamationsRepository $reclamationsRepository): Response
 {
     $searchTerm = $request->get('search_term');
 
     $queryBuilder = $reclamationsRepository->createQueryBuilder('r');
     $queryBuilder
         ->where($queryBuilder->expr()->orX(
             $queryBuilder->expr()->like('r.email', ':searchTerm'),
             $queryBuilder->expr()->like('r.description', ':searchTerm')
         ))
         ->setParameter('searchTerm', '%'. $searchTerm. '%');
 
     $searchResults = $queryBuilder->getQuery()->getResult();
 
     if (!$searchResults) {
         $searchResults = []; // Initialize an empty array when no results are found
     }
 
     return $this->render('reclamations/search.html.twig', [
         'reclamations' => $searchResults,
     ]);
 }
   /**
 * @Route("/reclamations/trier-par-email", name="trier_par_email")
 */
public function trierParEmail(ReclamationsRepository $reclamationsRepository): Response
{
    $reclamations = $reclamationsRepository->findBy([], ['email' => 'ASC']);

    return $this->render('reclamations/index.html.twig', [
        'reclamations' => $reclamations,
    ]);
}

/**
 * @Route("/reclamations/trier-par-etat", name="trier_par_etat")
 */
public function trierParEtat(ReclamationsRepository $reclamationsRepository): Response
{
    $reclamations = $reclamationsRepository->findBy([], ['etat' => 'ASC']);

    return $this->render('reclamations/index.html.twig', [
        'reclamations' => $reclamations,
    ]);
} 
/**
 * @Route("/reclamations/{id}/Reponse", name="add_reponse")
 */
public function add_reponse(Request $request, $id, ReclamationsRepository $reclamationsRepository): Response
{
    $reclamation = $reclamationsRepository->find($id);
    // Check if reclamation exists, handle if not found

    $response = new Reponse();

    $form = $this->createForm(ReponseType::class, $response, [
        'reclamation' => $reclamation,
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $reclamation->setEtat('Traité');
        $reclamation->setReponse($response);
        $reclamationsRepository->update($reclamation);
        $this->addFlash('success', 'Reclamation updated successfully');
        return $this->redirectToRoute('reclamations');
    }

    return $this->render('reponse/new.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $reclamation, // Pass the Reclamations entity to the template
        'email' => $reclamation->getEmail(),
        'typeReclamation' => $reclamation->getTypeReclamation(),
        'description' => $reclamation->getDescription(),
    ]);
}
/**
 * @Route("/reclamations/stat", name="stat")
 */
public function stat(): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $repository = $entityManager->getRepository(Reclamations::class);

    // Count total number of reclamations
    $totalReclamations = $repository->createQueryBuilder('r')
        ->select('COUNT(r.id)')
        ->getQuery()
        ->getSingleScalarResult();

    // Query for all reclamations and group them by type
    $query = $repository->createQueryBuilder('r')
        ->select('r.typeReclamation as type, COUNT(r.id) as count, COUNT(r.id) * 100 / :total as percentage')
        ->setParameter('total', $totalReclamations)
        ->groupBy('r.typeReclamation')
        ->getQuery();

    $reclamations = $query->getResult();

    // Calculate the counts array
    $counts = [];
    foreach ($reclamations as $reclamation) {
        $counts[$reclamation['type']] = $reclamation['count'];
    }

    return $this->render('reclamations/stat.html.twig', [
        'reclamations' => $reclamations,
        'counts' => $counts,
    ]);
}
/**
 * @Route("/reclamations/reponses", name="reponses")
 */
public function reponses(ReponseRepository $reponseRepository): Response
{
    $reponses = $reponseRepository->findAllWithReclamations();

    return $this->render('reclamations/reponses.html.twig', [
        'reponses' => $reponses,
    ]);
}

}
<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Entity\Equipe;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
use Dompdf\Options;
use Dompdf\Dompdf;


#[Route('/joueur')]
class JoueurController extends AbstractController
{   

    #[Route('/generate-pdf', name: 'app_generate_pdf', methods: ['GET'])]
    public function downloadCategoriesPdf(JoueurRepository $joueurRepository): Response
    {
        // Get all players from the repository
        $joueurs = $joueurRepository->findAll();
    
        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);
    
        // Generate PDF content with the players table
        $html = '<table border="1" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Age</th>
                            <th>Nationality</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($joueurs as $joueur) {
            $html .= '<tr>
                        <td>' . $joueur->getNom() . '</td>
                        <td>' . $joueur->getPrenom() . '</td>
                        <td>' . $joueur->getAge() . '</td>
                        <td>' . $joueur->getNationalite() . '</td>
                        <td>' . $joueur->getemail() . '</td>
                      </tr>';
        }
        $html .= '</tbody>
                </table>';
    
        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);
    
        // Render PDF
        $dompdf->render();
    
        // Stream PDF to client
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="joueurs.pdf"');
    
        return $response;
    }
  #[Route('/', name: 'app_joueur_index', methods: ['GET', 'POST'])]
   public function index(Request $request, JoueurRepository $joueurRepository): Response
{
    $searchQuery = $request->query->get('q');

    // If search query is present, fetch players based on search query
    if ($searchQuery) {
        $joueurs = $joueurRepository->findBySearchQuery($searchQuery);
    } else {
        // Otherwise, fetch all players
        $joueurs = $joueurRepository->findAll();
    }

    return $this->render('joueur/index.html.twig', [
        'joueurs' => $joueurs,
    ]);
}
    #[Route('/new', name: 'app_joueur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($joueur);
            $equipe = $entityManager->getRepository(Equipe::class)->find($joueur->getEquipe()->getId());
            $equipe->setNbJoueur($equipe->getNbJoueur() + 1);
            $entityManager->flush();
            $this->addFlash('success', 'Joueur ajouté avec succès! ');
            $account_sid = $_ENV['TWILIO_ACCOUNT_SID'];
            $auth_token = $_ENV['TWILIO_AUTH_TOKEN'];
            $twilio_number = $_ENV['TWILIO_PHONE_NUMBER'];
            $client = new Client($account_sid, $auth_token);
    
        
            $recipient_phone_number = '+21695103375';  
    
           

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('joueur/new.html.twig',['form'=>$form->createView()]);
    }

  

    #[Route('/{id}/edit', name: 'app_joueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $id, JoueurRepository $joueurRepository, EntityManagerInterface $entityManager): Response
    {
        $joueur = $joueurRepository->find($id);
        if (!$joueur) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Joueur modifié avec succès! ');

            return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('joueur/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form,
        ]);
    }

#[Route('/{id}', name: 'app_joueur_delete', methods: ['POST', 'GET'])]
public function delete(Request $request, $id, JoueurRepository $joueurRepository, EntityManagerInterface $entityManager): Response
{
    $joueur = $joueurRepository->find($id);
    if (!$joueur) {
        throw $this->createNotFoundException();
    }

    if ($this->isCsrfTokenValid('delete'.$joueur->getId(), $request->request->get('_token'))) {
        $equipe = $entityManager->getRepository(Equipe::class)->find($joueur->getEquipe()->getId());
        $equipe->setNbJoueur($equipe->getNbJoueur() - 1);
        $entityManager->remove($joueur);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_joueur_index', [], Response::HTTP_SEE_OTHER);
}
 
#[Route('/joueurs', name: 'joueurs', methods: ['GET'])]
public function indexfront(JoueurRepository $joueurRepository): Response
{
    $joueurs = $joueurRepository->findAll();

    return $this->render('joueur/indexfront.html.twig', [
        'joueurs' => $joueurs,
    ]);
}


}

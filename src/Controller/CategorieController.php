<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Form\ProduitType;

use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Writer\Csv;



#[Route('/categorie')]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager
            ->getRepository(Categorie::class)
            ->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/newProduit', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function newProduit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $originalExtension = $file->guessExtension();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $originalExtension;
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );
            }
            $produit->setImage($newFilename);
            $entityManager->persist($produit);
            $entityManager->flush();
            $this->addFlash('success', 'Produit créé avec succès! ');

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $categorie = new Categorie();
    $form = $this->createForm(CategorieType::class, $categorie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($categorie);
        $entityManager->flush();
        $this->addFlash('success', 'Categorie créé avec succès! ');

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('categorie/new.html.twig', [
        'categorie' => $categorie,
        'form' => $form,
    ]);
}

#[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
public function show(int $id, EntityManagerInterface $entityManager): Response
{
    // Retrieve the Categorie object from the database based on the $id value
    $categorie = $entityManager->getRepository(Categorie::class)->find($id);

    if (!$categorie) {
        throw $this->createNotFoundException('Categorie non trouvée.');
    }

    return $this->render('categorie/show.html.twig', [
        'categorie' => $categorie,
    ]);
}
#[Route('/{idcategorie}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, int $idcategorie, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager): Response
{
    $categorie = $categorieRepository->find($idcategorie);
    if (!$categorie) {
        throw $this->createNotFoundException();
    }

    $form = $this->createForm(CategorieType::class, $categorie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        $this->addFlash('success', 'Categorie modifié avec succès! ');

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('categorie/edit.html.twig', [
        'categorie' => $categorie,
        'form' => $form,
    ]);
}

#[Route('/{idcategorie}', name: 'app_categorie_delete', methods: ['POST'])]
public function delete(Request $request, int $idcategorie, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager): Response
{
    $categorie = $categorieRepository->find($idcategorie);
    if (!$categorie) {
        throw $this->createNotFoundException();
    }

    if ($this->isCsrfTokenValid('delete'.$idcategorie, $request->request->get('_token'))) {
        $entityManager->remove($categorie);
        $entityManager->flush();
        $this->addFlash('success', 'Categorie supprimé avec succès! ');
    }

    return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
}
    #[Route('/{idcategorie}/produits', name: 'app_categorie_produits', methods: ['GET'])]
    public function produits(int $idcategorie, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la catégorie par ID
        $categorie = $entityManager->getRepository(Categorie::class)->find($idcategorie);

        // Vérifier si la catégorie existe
        if (!$categorie) {
            throw $this->createNotFoundException('Catégorie non trouvée.');
        }

        // Récupérer les produits associés à cette catégorie
        $produits = $entityManager->getRepository(Produit::class)->findBy([
            'idcategorie' => $categorie,
        ]);

        // Rendre la vue Twig avec les produits
        return $this->render('categorie/produits.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }
    #[Route('/download-categories-pdf', name: 'app_categorie_download_pdf', methods: ['GET'])]
    public function downloadCategoriesPdf(CategorieRepository $categorieRepository): Response
    {
        // Récupérer la liste des catégories
        $categories = $categorieRepository->findAll();

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Générer le contenu PDF avec les catégories
        $html = $this->renderView('categorie/pdf.html.twig', [
            'categories' => $categories,
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Générer le PDF
        $dompdf->render();

        // Envoyer le PDF en réponse HTTP
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="categories.pdf"');

        return $response;
    }
    #[Route('/download-categories-excel', name: 'app_categorie_download_excel', methods: ['GET'])]
    public function downloadCategoriesExcel(CategorieRepository $categorieRepository): Response
    {

        // Récupérer la liste des catégories
        $categories = $categorieRepository->findAll();

        // Créer un nouvel objet Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Entêtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom');

        // Remplir les lignes avec les données des catégories
        $row = 2;
        foreach ($categories as $categorie) {
            $sheet->setCellValue('A' . $row, $categorie->getIdcategorie());
            $sheet->setCellValue('B' . $row, $categorie->getNomcategorie());
            $row++;
        }

        // Créer un objet Writer pour exporter en format CSV
        $writer = new Csv($spreadsheet);

        // Capturer la sortie générée par l'écriture du fichier CSV
        ob_start();
        $writer->save('php://output');
        $excelContent = ob_get_clean();

        // Créer une nouvelle réponse avec le contenu du fichier CSV
        $response = new Response($excelContent);

        // Définir les entêtes pour le téléchargement
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="liste_categories.csv"');
        $response->headers->set('Cache-Control', 'max-age=0');

        // Retourner la réponse
        return $response;
    }
    #[Route('/trier-par-nom', name: 'app_trier_par_nom', methods: ['GET'])]
    public function trierParNom(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findBy([], ['nomcategorie' => 'ASC']);

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

   
    

}

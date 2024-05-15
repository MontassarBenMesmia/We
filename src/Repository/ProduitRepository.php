<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    public function countProduitsByCategorie(): array
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('categorie', 'categorie');
        $rsm->addScalarResult('nombreProduits', 'nombreProduits');

        $query = $this->getEntityManager()->createNativeQuery('
            SELECT c.nomCategorie as categorie, COUNT(p.id) as nombreProduits
            FROM produit p
            LEFT JOIN categorie c ON p.idCategorie = c.idCategorie
            GROUP BY c.nomCategorie
        ', $rsm);

        return $query->getResult();
    }
     /**
     * Recherche des produits par nom.
     *
     * @param string $searchTerm Le terme de recherche par nom
     * @return Produit[] Une liste de produits correspondant au terme de recherche
     */
    public function rechercherParNom(string $searchTerm): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.nom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }
      /**
     * Trouver toutes les produitss triées par prix
     *
     * @return Produit[] Retourne un tableau d'objets de produit triés par prix
     */
    public function findAllOrderedByPrice(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

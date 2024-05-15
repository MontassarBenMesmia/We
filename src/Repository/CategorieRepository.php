<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }
    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getResult();
    }
     /**
     * Trouver toutes les catégories triées par nom
     *
     * @return Categorie[] Retourne un tableau d'objets de catégorie triés par nom
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nomcategorie', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // Ajoutez vos méthodes de repository personnalisées ici
}

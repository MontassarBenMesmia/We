<?php

namespace App\Repository;

use App\Entity\Reclamations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamations[]    findAll()
 * @method Reclamations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationsRepository extends ServiceEntityRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $_em;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamations::class);
        $this->_em = $registry->getManager();
    }

    public function add(Reclamations $reclamation): void
    {
        $this->_em->persist($reclamation);
        $this->_em->flush();
    }

    public function delete(Reclamations $reclamation): void
    {
        $this->_em->remove($reclamation);
        $this->_em->flush();
    }

    public function update(Reclamations $reclamation): void
    {
        $this->_em->persist($reclamation);
        $this->_em->flush();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findById(int $id): ?Reclamations
    {
        return $this->createQueryBuilder('r')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function searchReclamations(string $searchTerm): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.email LIKE :searchTerm OR r.typereclamation LIKE :searchTerm OR r.descriptionr LIKE :searchTerm OR r.etat LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getResult()
        ;
    }
}
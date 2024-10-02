<?php

namespace App\Repository;

use App\Entity\Prestataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prestataire>
 */
class PrestataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prestataire::class);
    }

    public function findByProjet($reference)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u')
            ->addSelect('c')
            ->addSelect('r')
            ->leftJoin('p.user', 'u')
            ->leftJoin('pos')
            ;
    }

    public function findAllPrestataire()
    {
        return $this->querySelect()->getQuery()->getResult();
    }

    public function findByMatricule($matricule)
    {
        return $this->querySelect()
            ->where('p.matricule = :matricule')
            ->setParameter('matricule', $matricule)
            ->getQuery()->getOneOrNullResult()
            ;
    }

    public function findByLocalite($localite)
    {
        return $this->querySelect()
            ->where('l.id = :localite')
            ->setParameter('localite', $localite)
            ->getQuery()->getResult()
            ;
    }

    public function findByCompetence($competence)
    {
        return $this->querySelect()
            ->where('c.id = :competence')
            ->setParameter('competence', $competence)
            ->getQuery()->getResult()
            ;
    }

    public function querySelect()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u')
            ->addSelect('l')
            ->addSelect('c')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.localite', 'l')
            ->leftJoin('p.competence', 'c');
    }

    //    /**
    //     * @return Prestataire[] Returns an array of Prestataire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Prestataire
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

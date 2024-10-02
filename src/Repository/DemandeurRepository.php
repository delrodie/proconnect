<?php

namespace App\Repository;

use App\Entity\Demandeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Demandeur>
 */
class DemandeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demandeur::class);
    }

    public function findAllDemandeur()
    {
        return $this->querySelect()->getQuery()->getResult();
    }

    public function querySelect(): QueryBuilder
    {
        return $this->createQueryBuilder('d')
            ->addSelect('u, l, m, pa')
            ->leftJoin('d.user', 'u')
            ->leftJoin('d.localite', 'l')
            ->leftJoin('d.messages', 'm')
            ->leftJoin('m.prestataire', 'pa')
            ;
    }

    //    /**
    //     * @return Demandeur[] Returns an array of Demandeur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Demandeur
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

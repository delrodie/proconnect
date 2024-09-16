<?php

namespace App\Repository;

use App\Entity\Postuler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postuler>
 */
class PostulerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postuler::class);
    }

    /**
     * Liste des candidatures par la reference du projet
     *
     * @param $reference
     * @return mixed
     */
    public function getCanditatureByProjet($reference)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('t')
            ->addSelect('u')
            ->leftJoin('p.projet', 't')
            ->leftJoin('p.user', 'u')
            ->where('t.reference = :reference')
            ->setParameter('reference', $reference)
            ->getQuery()->getResult();
    }

    /**
     * La candidature qui a été validée par le demandeur du projet
     *
     * @return mixed
     */
    public function findByProjetAndStatutDifferentOfAppel($projet): mixed
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u')
            ->addSelect('r')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.projet', 'r')
            ->where('p.statut <> :statut')
            ->andWhere('r.reference = :projet')
            ->setParameter('statut', 'APPEL')
            ->setParameter('projet', $projet)
            ->getQuery()->getSingleResult()
            ;
    }


    //    /**
    //     * @return Postuler[] Returns an array of Postuler objects
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

    //    public function findOneBySomeField($value): ?Postuler
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

<?php

namespace App\Repository;

use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Projet>
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    public function findAllByStatut(string $statut, $date = null, $budget = null)
    {
        $query =  $this->createQueryBuilder('p')
            ->where('p.statut = :statut')
            ->andWhere('p.dateLimite >= :date')
            ->setParameter('statut', $statut)
            ->setParameter('date', date('Y-m-d'))
        ;

        if ($date){
            $query->orderBy('p.dateLimite', 'ASC');
        }elseif ($budget){
            $query->orderBy('p.budgetMax', 'ASC');
        }else{
            $query->orderBy('p.id', 'ASC');
        }


        return $query->getQuery()->getResult();

    }

    public function findDetailsProjetByReference($reference)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('l')
            ->addSelect('u')
            ->addSelect('c')
            ->leftJoin('p.localite', 'l')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.categorie', 'c')
            ->where('p.reference = :reference')
            ->setParameter('reference', $reference)
            ->getQuery()->getSingleResult();
    }

    //    /**
    //     * @return Projet[] Returns an array of Projet objects
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

    //    public function findOneBySomeField($value): ?Projet
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

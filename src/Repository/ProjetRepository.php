<?php

namespace App\Repository;

use App\Entity\Projet;
use App\Service\Utilities;
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
        $query =  $this->querySelect()
            ->where('p.statut = :statut')
            ->andWhere('p.dateLimite >= :date')
            ->setParameter('statut', $statut)
            ->setParameter('date', date('Y-m-d H:i:s'))
            ->orderBy('p.dateLimite', 'ASC')
        ;

        if ($date){
            $query->addOrderBy('p.dateLimite', 'ASC');
        }elseif ($budget){
            $query->addOrderBy('p.budgetMax', 'ASC');
        }else{
            $query->addOrderBy('p.id', 'ASC');
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
            ->getQuery()->getOneOrNullResult();
    }

    public function findSimilaireByCategorie($categorie)
    {
        return $this->querySelect()
            ->where('c.id = :categorie')
            ->andWhere('p.dateLimite >= :date')
            ->setParameter('categorie', $categorie)
            ->setParameter('date', date('Y-m-d H:i:s'))
            ->orderBy('p.dateLimite', "ASC")
            ->getQuery()->getResult();
    }

    public function findByLocalite($localite, string $statut, $date = null, $budget = null)
    {
        $qb = $this->querySelect(); //dd($localite);

        // Ajouter une condition de tri par localité en utilisant CASE WHEN
        $qb->addSelect('(CASE WHEN l.id = :localite THEN 1 ELSE 0 END) AS HIDDEN priority')
            ->where('p.statut = :statut')
            ->andWhere('p.dateLimite >= :date')
            ->setParameter('statut', $statut)
            ->setParameter('date', date('Y-m-d H:i:s'))
            ->setParameter('localite', $localite->getId())
            ->orderBy('priority', 'DESC')
            ->addOrderBy('p.dateLimite', 'ASC')
            ;

        if ($date){
            $qb->addOrderBy('p.dateLimite', 'ASC');
        }elseif ($budget){
            $qb->addOrderBy('p.budgetMax', 'ASC');
        }else{
            $qb->addOrderBy('p.id', 'ASC');
        }

        return $qb->getQuery()->getResult();
    }

    public function findByLocaliteAndCategorie($localite, $categorie, string $statut)
    {
        $qb = $this->querySelect(); //dd($localite);

        // Ajouter une condition de tri par localité en utilisant CASE WHEN
        $qb->addSelect('(CASE WHEN l.id = :localite THEN 1 ELSE 0 END) AS HIDDEN priority')
            ->addSelect('(CASE WHEN c.id = :categorie THEN 1 ELSE 0 END) AS HIDDEN priority2')
            ->where('p.statut = :statut')
            ->andWhere('p.dateLimite >= :date')
            ->setParameter('statut', $statut)
            ->setParameter('date', date('Y-m-d H:i:s'))
            ->setParameter('localite', $localite->getId())
            ->setParameter('categorie', $categorie->getId())
            ->orderBy('priority', 'DESC')
            ->addOrderBy('priority2', 'DESC')
            ->addOrderBy('p.dateLimite', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function querySelect()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('l')
            ->addSelect('u')
            ->addSelect('c')
            ->leftJoin('p.localite', 'l')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.categorie', 'c')
            ;
    }

    public function findDepenseTotalByDemandeur($user)
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.montant)')
            ->where('p.user = :user')
            ->andWhere('p.statut = :statut')
            ->setParameter('user', $user)
            ->setParameter('statut', Utilities::PROJET_CLOTURE)
            ->getQuery()->getSingleScalarResult()
            ;
    }

    public function findAllClotureByDemandeur($user)
    {
        return $this->querySelect()
            ->where('p.user = :user')
            ->andWhere('p.statut = :statut')
            ->setParameter('user', $user)
            ->setParameter('statut', Utilities::PROJET_CLOTURE)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()->getResult()
            ;
    }

    public function findAllProjets()
    {
        return $this->querySelect()->getQuery()->getResult();
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

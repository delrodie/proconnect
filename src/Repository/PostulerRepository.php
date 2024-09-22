<?php

namespace App\Repository;

use App\Entity\Postuler;
use App\Service\Utilities;
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
            ->where('p.statut = :statut')
            ->andWhere('r.reference = :projet')
            ->setParameter('statut', Utilities::POSTULER_EMBAUCHE)
            ->setParameter('projet', $projet)
            ->getQuery()->getOneOrNullResult()
            ;
    }

    /**
     * Liste des projets validés par prestataire
     *
     * @param $user
     * @return mixed
     */
    public function findValideByPrestataire($user): mixed
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u')
            ->addSelect('r')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.projet', 'r')
            ->where('p.statut <> :statut')
            ->andWhere('p.user = :user')
            ->setParameter('statut', 'SOUMIS')
            ->setParameter('user', $user)
            ->orderBy('p.embaucheAt', 'DESC')
            ->getQuery()->getResult()
            ;
    }

    public function findProjetByUserAndCandidatureStatut($user, $statutProjet, $statutCandidature)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('u')
            ->addSelect('r')
            ->leftJoin('p.user', 'u')
            ->leftJoin('p.projet', 'r')
            ->where('p.statut = :statutCandidature')
            ->andWhere('p.user = :user')
            ->andWhere('r.statut = :statutProjet')
            ->setParameter('statutCandidature', $statutCandidature)
            ->setParameter('statutProjet', $statutProjet)
            ->setParameter('user', $user)
            ->orderBy('p.embaucheAt', 'DESC')
            ->getQuery()->getResult()
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

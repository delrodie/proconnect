<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getConversation($demandeur, $prestataire)
    {
        return $this->createQueryBuilder('m')
            ->addSelect('d')
            ->addSelect('p')
            ->leftJoin('m.demandeur', 'd')
            ->leftJoin('m.prestataire', 'p')
            ->where('d.code = :demandeur')
            ->andWhere('p.matricule = :prestataire')
            ->setParameter('demandeur', $demandeur)
            ->setParameter('prestataire', $prestataire)
            ->orderBy('m.id', 'ASC')
            ->getQuery()->getResult();
    }

    public function findByDemandeur($demandeur)
    {
        return $this->createQueryBuilder('m')
            ->addSelect('d')
            ->addSelect('p')
            ->leftJoin('m.demandeur', 'd')
            ->leftJoin('m.prestataire', 'p')
            ->where('d.code = :demandeur')
            ->setParameter('demandeur', $demandeur)
            ->orderBy('m.id', 'DESC')
            ->getQuery()->getResult();
    }

    public function findLastMessageByDemandeur($demandeur)
    {
        return $this->createQueryBuilder('m')
            ->addSelect('d')
            ->addSelect('p')
            ->leftJoin('m.demandeur', 'd')
            ->leftJoin('m.prestataire', 'p')
            ->where('d.code = :demandeur')
            ->andWhere('m.id IN (
                SELECT MAX(m2.id) 
                FROM App\Entity\Message m2
                WHERE m2.demandeur = m.demandeur
                GROUP BY m2.prestataire
            )')
            ->setParameter('demandeur', $demandeur)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findByPrestataire($prestataire)
    {
        return $this->createQueryBuilder('m')
            ->addSelect('d')
            ->addSelect('p')
            ->leftJoin('m.demandeur', 'd')
            ->leftJoin('m.prestataire', 'p')
            ->where('p.matricule = :prestataire')
            ->setParameter('prestataire', $prestataire)
            ->orderBy('m.id', 'DESC')
            ->getQuery()->getResult();
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}

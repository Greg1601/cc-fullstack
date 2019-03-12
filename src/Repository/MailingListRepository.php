<?php

namespace App\Repository;

use App\Entity\MailingList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MailingList|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailingList|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailingList[]    findAll()
 * @method MailingList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailingListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MailingList::class);
    }

    // /**
    //  * @return MailingList[] Returns an array of MailingList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MailingList
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

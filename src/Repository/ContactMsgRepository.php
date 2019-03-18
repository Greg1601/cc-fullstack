<?php

namespace App\Repository;

use App\Entity\ContactMsg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ContactMsg|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactMsg|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactMsg[]    findAll()
 * @method ContactMsg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactMsgRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ContactMsg::class);
    }

    // /**
    //  * @return ContactMsg[] Returns an array of ContactMsg objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactMsg
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

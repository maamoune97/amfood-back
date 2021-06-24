<?php

namespace App\Repository;

use App\Entity\OrderRefused;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderRefused|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderRefused|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderRefused[]    findAll()
 * @method OrderRefused[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRefusedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderRefused::class);
    }

    // /**
    //  * @return OrderRefused[] Returns an array of OrderRefused objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderRefused
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

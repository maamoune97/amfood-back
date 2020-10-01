<?php

namespace App\Repository;

use App\Entity\OrderArticlePack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderArticlePack|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderArticlePack|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderArticlePack[]    findAll()
 * @method OrderArticlePack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderArticlePackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderArticlePack::class);
    }

    // /**
    //  * @return OrderArticlePack[] Returns an array of OrderArticlePack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderArticlePack
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\ArticlePack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArticlePack|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticlePack|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticlePack[]    findAll()
 * @method ArticlePack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticlePackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticlePack::class);
    }

    // /**
    //  * @return ArticlePack[] Returns an array of ArticlePack objects
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
    public function findOneBySomeField($value): ?ArticlePack
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

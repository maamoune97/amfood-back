<?php

namespace App\Repository;

use App\Entity\TemporaryRestaurantPlainTextPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemporaryRestaurantPlainTextPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemporaryRestaurantPlainTextPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemporaryRestaurantPlainTextPassword[]    findAll()
 * @method TemporaryRestaurantPlainTextPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemporaryRestaurantPlainTextPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemporaryRestaurantPlainTextPassword::class);
    }

    // /**
    //  * @return TemporaryRestaurantPlainTextPassword[] Returns an array of TemporaryRestaurantPlainTextPassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemporaryRestaurantPlainTextPassword
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

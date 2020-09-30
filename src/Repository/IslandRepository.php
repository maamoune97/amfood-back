<?php

namespace App\Repository;

use App\Entity\Island;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Island|null find($id, $lockMode = null, $lockVersion = null)
 * @method Island|null findOneBy(array $criteria, array $orderBy = null)
 * @method Island[]    findAll()
 * @method Island[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IslandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Island::class);
    }

    // /**
    //  * @return Island[] Returns an array of Island objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Island
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

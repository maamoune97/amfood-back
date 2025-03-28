<?php

namespace App\Repository;

use App\Entity\RestaurantManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RestaurantManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantManager[]    findAll()
 * @method RestaurantManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantManager::class);
    }

    /**
     * @return RestaurantManager[] Returns an array of RestaurantManager objects
     */
    public function findAllSimplified()
    {
        return $this->createQueryBuilder('m')
            ->select('m.id, m.createdAt, r.name as restaurant, u.fullName, u.phone, u.email, c.name as city')
            ->groupBy('m.createdAt')
            ->orderBy('m.createdAt', 'DESC')
            ->join('m.user', 'u')
            ->join('m.restaurant', 'r')
            ->join('r.location', 'c')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return RestaurantManager[] Returns an array of RestaurantManager objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RestaurantManager
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

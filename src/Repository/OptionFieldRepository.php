<?php

namespace App\Repository;

use App\Entity\OptionField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OptionField|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionField|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionField[]    findAll()
 * @method OptionField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionField::class);
    }

    // /**
    //  * @return OptionField[] Returns an array of OptionField objects
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
    public function findOneBySomeField($value): ?OptionField
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

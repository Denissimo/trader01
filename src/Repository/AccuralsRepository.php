<?php

namespace App\Repository;

use App\Entity\Accurals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Accurals|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accurals|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accurals[]    findAll()
 * @method Accurals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccuralsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accurals::class);
    }

    // /**
    //  * @return Accurals[] Returns an array of Accurals objects
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
    public function findOneBySomeField($value): ?Accurals
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

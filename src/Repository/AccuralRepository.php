<?php

namespace App\Repository;

use App\Entity\Accural;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Accural|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accural|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accural[]    findAll()
 * @method Accural[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccuralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accural::class);
    }

    public function findByUserGroupByLevel(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('a');

        $res = $qb
//            ->select('a')
            ->select('a.level')
            ->addSelect('count(a) as count')
            ->addSelect('sum(a.amountUsd) as amountUsd')
            ->addSelect('sum(a.amountBtc) as amountBtc')
            ->addSelect('sum(a.amountEth) as amountEth')
            ->andWhere('a.user = :val')
            ->groupBy('a.level')
            ->orderBy('a.level')
            ->setParameter('val', $user);
        $dql = $qb->getDQL();

            return $res->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Accural[] Returns an array of Accural objects
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
    public function findOneBySomeField($value): ?Accural
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

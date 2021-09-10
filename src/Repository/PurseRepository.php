<?php

namespace App\Repository;

use App\Entity\Currency;
use App\Entity\Purse;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Purse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purse[]    findAll()
 * @method Purse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purse::class);
    }

    /**
     * @param User $user
     *
     * @return $this
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createPursesForUser(User $user)
    {
        $currencies = $this->getEntityManager()->getRepository(Currency::class)->findAll();

        foreach ($currencies as $currency) {
            $purse = (new Purse())->setUser($user)
                ->setCurrency($currency);

            $this->getEntityManager()->persist($purse);
        }

        $this->getEntityManager()->flush();

        return $this;
    }

    // /**
    //  * @return Purse[] Returns an array of Purse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Purse
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserTree|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTree|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTree[]    findAll()
 * @method UserTree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTree::class);
    }

    public function buildTree(User $user)
    {
        $level = 0;
        $currentUser = $user;

        while ($user->getParent() instanceof User) {
            $level++;
            if ($level > UserTree::PARENT_LEVEL_MAX) {

                return $this;
            }

            $userTreeExist = $this->findOneBy([
                'childUser' => $currentUser,
                'parentUser' => $user->getParent(),
            ]);

            if ($userTreeExist instanceof UserTree) {

                return $this;
            }
            $userTree = (new UserTree())->setChildUser($currentUser)
                ->setParentUser($user->getParent())
                ->setLevel($level);
            $this->getEntityManager()
                ->persist($userTree);

            $user = $user->getParent();
        }

        return $this;
    }

    public function findChildren(User $user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.childUser = :val')
            ->setParameter('val', $user)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findParents(User $user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.parentUser = :val')
            ->setParameter('val', $user)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function findChildrenGroupByLevel(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('u');

        $res = $qb
            ->select('u.level')
            ->addSelect('count(u) as count')
            ->andWhere('u.parentUser = :val')
            ->groupBy('u.level')
            ->orderBy('u.level')
            ->setParameter('val', $user);

        return $res->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return UserTree[] Returns an array of UserTree objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserTree
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

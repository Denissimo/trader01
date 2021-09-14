<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

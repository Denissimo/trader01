<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public const DEFAULT_PASSWORD = '123456';

    public const DEFAULT_PREFIX = 'user_';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordHasher;

    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $passwordHasher)
    {
        parent::__construct($registry, User::class);

        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param User|null $parent
     * @param string|null $username
     * @param string $password
     *
     * @return User
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\ORM\ORMException
     */
    public function create(?User $parent, string $username = null, string $password = self::DEFAULT_PASSWORD)
    {
        $user = new User();
        $username = $username ?? $this->generateName();
        $user->setUsername($username)
            ->setParent($parent)
            ->setPassword(
                $this->passwordHasher->encodePassword(
                    $user,
                    $password
                )
            );
        $this->getEntityManager()
            ->persist($user);

        $this->getEntityManager()
            ->getRepository(Account::class)
            ->createAccountForUser($user);

        return $user;
    }

    /**
     * @param string|null $prefix
     *
     * @return string
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    private function generateName(?string $prefix = self::DEFAULT_PREFIX)
    {
        $lastId = $this->getEntityManager()
            ->getConnection()
            ->executeQuery("SELECT last_value FROM user_id_seq")
            ->fetchOne();

        return sprintf('user_%d', $lastId);
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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

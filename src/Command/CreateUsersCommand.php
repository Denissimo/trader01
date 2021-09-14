<?php

namespace App\Command;

use App\Entity\Account;
use App\Entity\Purse;
use App\Entity\User;
use Throwable;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUsersCommand extends Command
{
    const DEFAULT_QTY = 5000;

    protected static $defaultName = 'app:users:create';

    /**
     * @var int
     */
    private static $createdQty = 0;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordHasher;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordHasher
    )
    {
        parent::__construct(null);
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure()
    {
        $this
            ->setName('app:users:create')
            ->setDescription('Its a DB Test.')
            ->setHelp('DB test command')
            ->addOption(
                'number',
                null,
                InputOption::VALUE_OPTIONAL,
                'How many Users',
                self::DEFAULT_QTY);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->create(null, $input->getOption('number'));
        $this->entityManager->flush();
        $output->writeln('Created users: ' . self::$createdQty);

        return 0;
    }

    /**
     * @param User|null $user
     * @param int $number
     *
     * @return $this
     */
    private function create(?User $user = null, int $number = self::DEFAULT_QTY)
    {
        try {
            $min = $user instanceof User ? 0 : 3;
            $newUsersNumber = rand($min, 3);
            for ($i = 0; $i <= $newUsersNumber; $i++) {
                if (self::$createdQty >= $number) {
                    return $this;
                }
                $parent = $user;
                $parentId = $parent instanceof User ? $parent->getId() : 'No';
                $user = new User();
                $lastId = $this->entityManager->getConnection()->executeQuery("SELECT last_value FROM user_id_seq")->fetchOne();
                $username = sprintf('user_%d', $lastId);
                $user->setUsername($username)
                    ->setParent($parent)
                    ->setPassword(
                        $this->passwordHasher->encodePassword(
                            $user,
                            User::DAFAULT_PASSWORD
                        )
                    );
                $this->entityManager->persist($user);

                $this->entityManager->getRepository(Purse::class)
                    ->createPursesForUser($user);
                $this->entityManager->getRepository(Account::class)
                    ->createAccountForUser($user);

                self::$createdQty++;
                $this->output->writeln(
                    sprintf('Created user: %s; Parent: %s', $user->getUserIdentifier(), $parentId)
                );


                $hasChild = rand($min, 10) > 3;

                if($hasChild) {
                    $this->create($user, $number);
                } else {
                    return $this;
                }
            }


            return $this;
        } catch (Throwable $e) {
            $this->output->writeln($e->getMessage());

            return $this;
        }
    }
}
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

class GenerateUsersCommand extends Command
{
    const DEFAULT_LEVELS = 12;
    const USERS_MAX = 3;

    protected static $defaultName = 'app:users:generate';

    /**
     * @var int
     */
    private static $createdQty = 0;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct(null);
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:users:generate')
            ->setDescription('Users generate script.')
            ->setHelp('Generate users command')
            ->addOption(
                'levels',
                null,
                InputOption::VALUE_OPTIONAL,
                'How many levels',
                self::DEFAULT_LEVELS);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->generate();
        $this->entityManager->flush();
        $output->writeln('Created users: ' . self::$createdQty);

        return 0;
    }

    private function generate(?int $levels = self::DEFAULT_LEVELS)
    {
        try {
            $usersMin = 3;
            $usersNumber = rand($usersMin, self::USERS_MAX);
            $currentUsersList = array_fill(0, $usersNumber, null);
            for ($level = 0; $level < $levels; $level++) {
                $newUsersList = [];
                foreach ($currentUsersList as $parent) {
                    $usersNumber = rand($usersMin, self::USERS_MAX);
                    for($userNum = 0; $userNum < $usersNumber; $userNum++) {
                        $user = $this->entityManager->getRepository(User::class)
                            ->create($parent);
                        self::$createdQty++;
                        $newUsersList[] = $user;
                        $parentId = $parent instanceof User ? $parent->getId() : 'No';
                        $this->output->writeln(
                            sprintf(
                                'Created %d user of level %d: %s; Parent: %s',
                                self::$createdQty,
                                $level,
                                $user->getUserIdentifier(),
                                $parentId)
                        );
                    }
                }
                $this->entityManager->flush();
                $currentUsersList = $newUsersList;
                $usersMin = 0;
            }
            $this->entityManager->flush();

        } catch (Throwable $e) {
            $this->output->writeln($e->getMessage());

            return $this;
        }

        return $this;
    }
}
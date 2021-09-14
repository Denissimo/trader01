<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\Criteria;
use App\Entity\Deal;
use App\DTO\Deal as DealResponse;

class DealGenerator
{
    private const DEFAULT_NUMBER = 500;
    private const RANDOM_MAX = 100;

    /**
     * @var EntityManagerInterface;
     */
    private $entityManager;

    /**
     * DealGenerator constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate(int $number = self::DEFAULT_NUMBER, int $min = null)
    {
        /** @var User[] $users */
        $users = $this->loadUsers($number, $min);

        $deals = [];

        foreach($users as $user) {
            $isDeal = false;
            $amountUsd = rand(0, 1) ? rand(0, self::RANDOM_MAX) / 10**Account::SCALE_USD : 0;
            $amountBtc = rand(0, 1) ? rand(0,self::RANDOM_MAX) / 10**Account::SCALE_BTC : 0;
            $amountEth= rand(0, 1) ? rand(0,self::RANDOM_MAX) / 10**Account::SCALE_ETH : 0;
            $balanceUsd = $user->getAccount()->getAmountUsd();
            $balanceBtc = $user->getAccount()->getAmountBtc();
            $balanceEth = $user->getAccount()->getAmountEth();
            $deal = (new Deal())->setUser($user)
                ->setPurpose('Test Daeal by DealGenerator');

            if ($balanceUsd > $amountUsd) {
                $isDeal = true;
                $deal->setAmountUsd($amountUsd);
                $user->getAccount()->setAmountUsd($balanceUsd - $amountUsd);
            }

            if ($balanceBtc > $amountBtc) {
                $isDeal = true;
                $deal->setAmountBtc($amountBtc);
                $user->getAccount()->setAmountBtc($balanceBtc - $amountBtc);
            }

            if ($balanceEth > $amountEth) {
                $isDeal = true;
                $deal->setAmountEth($amountEth);
                $user->getAccount()->setAmountEth($balanceEth - $amountEth);
            }
            
            if ($isDeal){
                $this->entityManager->persist($deal);
                $deals[] = new DealResponse($deal);
            }
        }

        return $deals;
    }

    private function loadUsers(int $number = self::DEFAULT_NUMBER, int $min = null)
    {
        $lastId = $this->entityManager->getConnection()
            ->executeQuery("SELECT last_value FROM user_id_seq")
            ->fetchOne();
        $offset = $min ?? rand(1, $lastId - $number);
        $criteria = Criteria::create()->setMaxResults($number)
            ->setFirstResult($offset)
            ->orderBy(['id' => 'ASC'])
        ;

        return $this->entityManager->getRepository(User::class)
            ->matching($criteria)
            ->toArray();
    }
}
<?php

namespace App\Service;

use App\DTO\DealUnit;
use App\DTO\LevelUnit;
use App\Entity\Accural;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RewardCounter
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $pathApiFull;

    /**
     * @var EntityManagerInterface;
     */
    private $entityManager;

    /**
     * @var LevelUnit[]|array
     */
    public $levelTree;


    /**
     * RewardCounter constructor.
     *
     * @param HttpClientInterface $client
     * @param EntityManagerInterface $entityManager
     * @param string $pathDeal
     * @param string $urlApi
     */
    public function __construct(
        HttpClientInterface $client,
        EntityManagerInterface $entityManager,
        string $pathDeal,
        string $urlApi
    )
    {
        $this->client = $client;
        $this->pathApiFull = $urlApi . $pathDeal;
        $this->entityManager = $entityManager;
    }

    public function getDeals()
    {
        $dealsResponse = $this->client->request('GET', $this->pathApiFull);
        $content = $dealsResponse->getContent();

        return json_decode($content);
    }

    /**
     * @param array $deals
     *
     * @return array
     */
    public function loadTree(array $deals)
    {
        $dealsCount = count($deals);
        $userList = [];
        foreach ($deals as $deal) {
            $dealUnit = new DealUnit($deal);
            /** @var User $user */
            $user = $this->entityManager->getRepository(User::class)
                ->find($dealUnit->userId);
            $userList[$user->getId()] = null;
            /** @var UserTree[]|array $parentsTree */
            $parentsTree = $this->entityManager->getRepository(UserTree::class)
                ->findChildren($user);
            $summaryAmountUsd = 0;
            $summaryAmountBtc = 0;
            $summaryAmountEth = 0;

            $summaryAwardUsd = 0;
            $summaryAwardBtc = 0;
            $summaryAwardEth = 0;

            foreach ($parentsTree as $tree) {
                $level = $tree->getLevel();
                $parent = $tree->getParentUser();
                $percent = UserTree::$levels[$level];
                $awardUsd = $dealUnit->amountUsd * $percent;
                $awardBtc = $dealUnit->amountBtc * $percent;
                $awardEth = $dealUnit->amountEth * $percent;

                $summaryAmountUsd += $dealUnit->amountUsd;
                $summaryAmountBtc += $dealUnit->amountBtc;
                $summaryAmountEth += $dealUnit->amountEth;

                $summaryAwardUsd += $awardUsd;
                $summaryAwardBtc += $awardBtc;
                $summaryAwardEth += $awardEth;


                $accural = (new Accural())
                    ->setUser($parent)
                    ->setSourceUser($user)
                    ->setLevel($level)
                    ->setAmountUsd($awardUsd)
                    ->setAmountBtc($awardBtc)
                    ->setAmountEth($awardEth)
                    ->setComment('Authomatical rewards count');


                $this->entityManager
                    ->persist($accural);
            }
            $this->entityManager->flush();
        }

        $rewardStat = [
            'deals' => $deals,
            'users' => count($userList),
            'amountUsd' => $summaryAmountUsd,
            'amountBtc' => $summaryAmountBtc,
            'amountEth' => $summaryAmountEth,
            'awardUsd' => $summaryAwardUsd,
            'awardBtc' => $summaryAwardBtc,
            'awardEth' => $summaryAwardEth,
        ];

        return $rewardStat;
    }

    public function buildTree(array $deals)
    {
        $userCount = [];
        foreach ($deals as $deal) {
            $dealUnit = new DealUnit($deal);
            /** @var User $user */
            $user = $this->entityManager->getRepository(User::class)
                ->find($dealUnit->userId);
            $userCount[$user->getId()] = null;
            $ansisters = [];
            $depth = 0;
            $currentUser = $user;
            while($currentUser->getParent() instanceof User && $depth < User::PARENT_LEVEL_MAX) {
                $depth++;
                $ansisters[$depth] = $currentUser->getParent();
                $currentUser = $currentUser->getParent();
            }
            $summaryAmountUsd = 0;
            $summaryAmountBtc = 0;
            $summaryAmountEth = 0;

            $summaryAwardUsd = 0;
            $summaryAwardBtc = 0;
            $summaryAwardEth = 0;

            foreach ($ansisters as $level => $ansister) {
                $percent = User::$levels[$level];
                $awardUsd = $dealUnit->amountUsd * $percent;
                $awardBtc = $dealUnit->amountBtc * $percent;
                $awardEth = $dealUnit->amountEth * $percent;

                $summaryAmountUsd += $dealUnit->amountUsd;
                $summaryAmountBtc += $dealUnit->amountBtc;
                $summaryAmountEth += $dealUnit->amountEth;

                $summaryAwardUsd += $awardUsd;
                $summaryAwardBtc += $awardBtc;
                $summaryAwardEth += $awardEth;


                $accural = (new Accural())
                    ->setUser($ansister)
                    ->setSourceUser($user)
                    ->setLevel($level)
                    ->setAmountUsd($awardUsd)
                    ->setAmountBtc($awardBtc)
                    ->setAmountEth($awardEth)
                    ->setComment('Authomatical rewards count');


                $this->entityManager
                    ->persist($accural);
            }

            $this->entityManager->flush();

            $rewardStat = [
                'deals' => $deals,
                'users' => count($userCount),
                'amountUsd' => $summaryAmountUsd,
                'amountBtc' => $summaryAmountBtc,
                'amountEth' => $summaryAmountEth,
                'awardUsd' => $summaryAwardUsd,
                'awardBtc' => $summaryAwardBtc,
                'awardEth' => $summaryAwardEth,
            ];
        }

        return $rewardStat;
    }
}
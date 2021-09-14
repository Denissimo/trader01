<?php

namespace App\Service;

use App\DTO\DealUnit;
use App\DTO\LevelUnit;
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
     * @var [][]
     */
    private $levels;

    /**
     * @var [][]
     */
    private $userTree = [];

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
     * @return $this
     */
    public function buildTree(array $deals)
    {
        /** @var User[]|array $users */
        $users = [];
        foreach ($deals as $deal) {
            $dealUnit = new DealUnit($deal);
            /** @var User $user */
            $user = $this->entityManager->getRepository(User::class)
                ->find($dealUnit->userId);

            while($user->getParent() instanceof User) {
                $parent = $user->getParent();
                $parentLevelUnit = $this->levelTree[$parent->getId()] ?? new  LevelUnit($parent);
                $currentLevelUnit =  $this->levelTree[$user->getId()] ?? new  LevelUnit($user);
                $dealUnit = new DealUnit($deal);
                $currentLevelUnit->pushDeal($dealUnit);
                $this->levelTree[$parent->getId()] = $parentLevelUnit;
                $this->levelTree[$parent->getId()]->pushChild($currentLevelUnit);
//                unset($this->levelTree[$user->getId()]);

                $this->userTree[$parent->getId()][$user->getId()] = null;

                $user = $parent;
            }
        }

        foreach ($this->levelTree as $levelUnit) {
            $this->levelTree[$levelUnit->getUser()->getId()] = $this->buildLevels($levelUnit);
        }

        ksort($this->userTree);

        return $this;
    }

    private function buildLevels(LevelUnit $levelUnit, int $level = 0)
    {
//        if ($level > User::LEVEL_MAX) {
//            return $this;
//        }
        $children = $levelUnit->getChildren();

        $newLevel = $level + 1;

        if($newLevel < User::LEVEL_MAX && $levelUnit->getUser()->getParent() instanceof User) {
            $parentId = $levelUnit->getUser()->getParent()->getId();
            $userId = $levelUnit->getUser()->getId();
            $this->levels[$parentId][$userId] = $newLevel;
        }

        foreach($children as $child) {
            $levelUnitChild = $this->buildLevels($child, $newLevel);
            $levelUnit->pushChild($levelUnitChild);
        }

//        $levelUnit->setLevel($level);
//        $this->levelTree[$levelUnit->getUser()->getId()] = $levelUnit;

        return $levelUnit;
    }

    private function countLevels(int $level = 0)
    {
        foreach ($this->userTree as $userUnit) {

        }
    }
}
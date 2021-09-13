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
    private $levelTree;

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
                $levelUnit =  new  LevelUnit();
                $parent = $user->getParent();
                $dealUnit = new DealUnit($deal);
                $levelUnit->pushDeal($dealUnit);
                $this->levelTree[$parent->getId()][$user->getId()] = $levelUnit;
                $user = $parent;
            }
        }


        return $this;
    }

    private function countLevels(LevelUnit $levelUnit, int $level = 0)
    {

    }
}
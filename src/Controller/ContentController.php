<?php

namespace App\Controller;

use App\Service\DealGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\RewardCounter;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContentController extends AbstractController
{
    /**
     * @var  TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var  DealGenerator
     */
    private $gealGenerator;

    /**
     * ContentController constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param DealGenerator $gealGenerator
     */
    public function __construct(TokenStorageInterface $tokenStorage, DealGenerator $gealGenerator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->gealGenerator = $gealGenerator;
    }


    public function buildMain(Request $request)
    {
        return $this->render('main.html.twig', []);
    }

    public function buildAccount(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        return $this->render('account.html.twig', [
            'user' => $user
        ]);
    }

    public function buildDeal()
    {
        $deals = $this->gealGenerator->generate();

        return new JsonResponse($deals);
    }

    public function buildReward(RewardCounter $rewardCounter)
    {
        $rewards = $rewardCounter->getDeals();

        return $this->render('reward.html.twig', [
            'rewards' => $rewards
        ]);
    }
}
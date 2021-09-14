<?php

namespace App\Controller;

use App\Entity\Accural;
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

        $accurals = $this->getDoctrine()
            ->getManager()
            ->getRepository(Accural::class)
            ->findByUserGroupByLevel($user);

        return $this->render('account.html.twig', [
            'user' => $user,
            'accurals' => $accurals
        ]);
    }

    public function buildDeal()
    {
        $deals = $this->gealGenerator->generate(50, 500);

        return new JsonResponse($deals);
    }

    public function buildReward(RewardCounter $rewardCounter)
    {
        $rewards = $rewardCounter->getDeals();
        $rewardStat = $rewardCounter->buildTree($rewards);

        return new JsonResponse($rewardStat);
    }
}
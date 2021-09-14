<?php

namespace App\Controller;

use App\Entity\Accural;
use App\Entity\UserTree;
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

        $childrenGrouped = $this->getDoctrine()
            ->getManager()
            ->getRepository(UserTree::class)
            ->findChildrenGroupByLevel($user);


        $childrenLevels = array_column($childrenGrouped, 'level');
        $childrenCombile = array_combine($childrenLevels, $childrenGrouped);
        $accuralLevels = array_column($accurals, 'level');
        $accuralCombile = array_combine($accuralLevels, $accurals);


        return $this->render('account.html.twig', [
            'user' => $user,
            'children' => $childrenCombile,
            'accurals' => $accuralCombile
        ]);
    }

    public function buildDeal()
    {
        $deals = $this->gealGenerator->generate(50);

        return new JsonResponse($deals);
    }

    public function buildReward(RewardCounter $rewardCounter)
    {
        $rewards = $rewardCounter->getDeals();
        $rewardStat = $rewardCounter->buildTree($rewards);

        return new JsonResponse($rewardStat);
    }
}
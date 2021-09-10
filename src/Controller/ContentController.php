<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ContentController extends AbstractController
{
    /**
     * @var  TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * DocumentController constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
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
}
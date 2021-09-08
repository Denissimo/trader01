<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    public function buildContent(Request $request)
    {
        return $this->render('lk.html.twig', [
            'id' => 155,
            'use_arcgis' => false
        ]);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ContentController extends AbstractController
{
    public function buildContent(Request $request)
    {
        return $this->render('main.html.twig', [
            'id' => 155,
            'use_arcgis' => false
        ]);
    }
}
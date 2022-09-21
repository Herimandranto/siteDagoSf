<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitebaseController extends AbstractController
{
    #[Route('/dago', name: 'app_sitebase')]
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SitebaseController',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlocController extends AbstractController
{
    #[Route('/bloc', name: 'app_bloc')]
    public function index(): Response
    {
        return $this->render('bloc/index.html.twig', [
            'controller_name' => 'BlocController',
        ]);
    }
}

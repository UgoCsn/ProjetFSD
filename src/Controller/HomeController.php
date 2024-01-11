<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        $allProduct = $productRepository->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'allProduct' => $allProduct,
        ]);
    }

    #[Route('/CGV', name: 'app_CGV')]
    public function CGV(): Response
    {
        return $this->render('home/CGV.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/CGU', name: 'app_CGU')]
    public function CGU(): Response
    {
        return $this->render('home/CGU.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/confidential', name: 'app_confidential')]
    public function confidential(): Response
    {
        return $this->render('home/confidential.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/legal', name: 'app_legal')]
    public function legal(): Response
    {
        return $this->render('home/legal.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}

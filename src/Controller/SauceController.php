<?php

namespace App\Controller;

use App\Repository\SauceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sauces', name: 'sauce_')]
class SauceController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SauceRepository $sauceRepository): Response
    {
        $sauces = $sauceRepository->findAll();
        return $this->render('sauce/index.html.twig', [
            'sauces' => $sauces,
        ]);
    }
}
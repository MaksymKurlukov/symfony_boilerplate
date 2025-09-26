<?php

namespace App\Controller;

use App\Repository\PainRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pains', name: 'pain_')]
class PainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PainRepository $painRepository): Response
    {
        $pains = $painRepository->findAll();
        return $this->render('pain/index.html.twig', [
            'pains' => $pains,
        ]);
    }
}
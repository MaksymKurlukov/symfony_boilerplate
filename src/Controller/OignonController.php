<?php

namespace App\Controller;

use App\Repository\OignonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/oignons', name: 'oignon_')]
class OignonController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(OignonRepository $oignonRepository): Response
    {
        $oignons = $oignonRepository->findAll();
        return $this->render('oignon/index.html.twig', [
            'oignons' => $oignons,
        ]);
    }
}
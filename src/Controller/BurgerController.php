<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/burgers', name: 'burger_')]
class BurgerController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function list(): Response
    {
        return $this->render('burgers_list.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        $burgers = [
            1 => ['id' => 1, 'name' => 'Crabe Croustillant', 'description' => 'Un burger délicieux avec un pâté de crabe spécial.'],
            2 => ['id' => 2, 'name' => 'Burger Bob', 'description' => 'Le classique de Bob l\'Éponge, avec des ingrédients secrets.'],
            3 => ['id' => 3, 'name' => 'Veggie Krab', 'description' => 'Un burger végétarien pour les amis de la mer.'],
        ];

        if (!isset($burgers[$id])) {
            throw new NotFoundHttpException('Burger non trouvé');
        }

        return $this->render('burger_show.html.twig', [
            'burger' => $burgers[$id],
        ]);
    }
}
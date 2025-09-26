<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Entity\Pain;
use App\Entity\Image;
use App\Entity\Oignon;
use App\Entity\Sauce;
use App\Repository\BurgerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
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
    public function show(int $id, ManagerRegistry $doctrine): Response
    {
        $burger = $doctrine->getRepository(Burger::class)->find($id);
        if (!$burger) {
            throw new NotFoundHttpException('Burger non trouvé');
        }
        return $this->render('burger_show.html.twig', ['burger' => $burger]);
    }

    #[Route('/', name: 'index')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findAll();
        return $this->render('burger/index.html.twig', ['burgers' => $burgers]);
    }

    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $burger->setName('Krabby Patty');
        $burger->setPrice('4.99');

        $pain = new Pain();
        $pain->setName('Classique');
        $entityManager->persist($pain);

        $image = new Image();
        $image->setName('krabby_patty.jpg');
        $entityManager->persist($image);

        $oignon = new Oignon();
        $oignon->setName('Rouge');
        $entityManager->persist($oignon);

        $sauce = new Sauce();
        $sauce->setName('Ketchup');
        $entityManager->persist($sauce);

        $burger->setPain($pain);
        $burger->setImage($image);
        $burger->addOignon($oignon);
        $burger->addSauce($sauce);

        $entityManager->persist($burger);
        $entityManager->flush();
        return new Response('Burger créé avec succès !');
    }

    #[Route('/expensive', name: 'expensive')]
    public function expensive(BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findExpensiveBurgers(5.0);
        return $this->render('burger/expensive.html.twig', ['burgers' => $burgers]);
    }

    #[Route('/range', name: 'range')]
    public function range(BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersInRange(4.0, 6.0);
        return $this->render('burger/range.html.twig', ['burgers' => $burgers]);
    }

    #[Route('/with-ingredient/{ingredient}', name: 'with_ingredient')]
    public function withIngredient(string $ingredient, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersWithIngredient($ingredient);
        return $this->render('burger/with_ingredient.html.twig', [
            'burgers' => $burgers,
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/top/{limit}', name: 'top_x', requirements: ['limit' => '\d+'])]
    public function topX(int $limit, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findTopXBurgers($limit);
        return $this->render('burger/top_x.html.twig', [
            'burgers' => $burgers,
            'limit' => $limit,
        ]);
    }

    #[Route('/without-ingredient/{ingredient}', name: 'without_ingredient')]
    public function withoutIngredient(string $ingredient, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersWithoutIngredient($ingredient);
        return $this->render('burger/without_ingredient.html.twig', [
            'burgers' => $burgers,
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/min-ingredients/{minIngredients}', name: 'min_ingredients', requirements: ['minIngredients' => '\d+'])]
    public function withMinimumIngredients(int $minIngredients, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersWithMinimumIngredients($minIngredients);
        return $this->render('burger/min_ingredients.html.twig', [
            'burgers' => $burgers,
            'minIngredients' => $minIngredients,
        ]);
    }
}
<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    public function findExpensiveBurgers(float $price): array
    {
        $qb = $this->createQueryBuilder('b')
                   ->where('b.price > :price')
                   ->setParameter('price', $price)
                   ->orderBy('b.price', 'DESC');
        return $qb->getQuery()->getResult();
    }

    public function findBurgersInRange(float $minPrice, float $maxPrice): array
    {
        $qb = $this->createQueryBuilder('b')
                   ->where('b.price >= :minPrice')
                   ->andWhere('b.price <= :maxPrice')
                   ->setParameter('minPrice', $minPrice)
                   ->setParameter('maxPrice', $maxPrice)
                   ->orderBy('b.price', 'ASC');
        return $qb->getQuery()->getResult();
    }

    public function findBurgersWithIngredient(string $ingredient): array
    {
        $qb = $this->createQueryBuilder('b')
                   ->innerJoin('b.oignons', 'o')
                   ->where('o.name = :ingredient')
                   ->setParameter('ingredient', $ingredient);
        return $qb->getQuery()->getResult();
    }

    public function findTopXBurgers(int $limit): array
    {
        $qb = $this->createQueryBuilder('b')
                   ->orderBy('b.price', 'DESC')
                   ->setMaxResults($limit);
        return $qb->getQuery()->getResult();
    }

    public function findBurgersWithoutIngredient(string $ingredient): array
    {
        $qb = $this->createQueryBuilder('b')
                   ->leftJoin('b.sauces', 's')
                   ->where('s.name != :ingredient OR s.name IS NULL')
                   ->setParameter('ingredient', $ingredient);
        return $qb->getQuery()->getResult();
    }

    public function findBurgersWithMinimumIngredients(int $minIngredients): array
    {
        $qb = $this->createQueryBuilder('b')
                   ->leftJoin('b.oignons', 'o')
                   ->leftJoin('b.sauces', 's')
                   ->groupBy('b.id')
                   ->having('COUNT(o.id) + COUNT(s.id) >= :minIngredients')
                   ->setParameter('minIngredients', $minIngredients);
        return $qb->getQuery()->getResult();
    }
}
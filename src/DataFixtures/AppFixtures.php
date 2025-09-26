<?php

namespace App\DataFixtures;

use App\Entity\Pain;
use App\Entity\Oignon;
use App\Entity\Sauce;
use App\Entity\Image;
use App\Entity\Commentaire;
use App\Entity\Burger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const PAIN_REFERENCE = 'Pain';
    private const OIGNON_REFERENCE = 'Oignon';
    private const SAUCE_REFERENCE = 'Sauce';
    private const IMAGE_REFERENCE = 'Image';
    private const BURGER_REFERENCE = 'Burger';

    public function load(ObjectManager $manager): void
    {
        // Pains
        $pains = ['Classique', 'Sésame', 'Complet'];
        foreach ($pains as $key => $name) {
            $pain = new Pain();
            $pain->setName($name);
            $manager->persist($pain);
            $this->addReference(self::PAIN_REFERENCE . '_' . $key, $pain);
        }

        // Oignons
        $oignons = ['Rouge', 'Blanc', 'Caramélisé'];
        foreach ($oignons as $key => $name) {
            $oignon = new Oignon();
            $oignon->setName($name);
            $manager->persist($oignon);
            $this->addReference(self::OIGNON_REFERENCE . '_' . $key, $oignon);
        }

        // Sauces
        $sauces = ['Blanche', 'Mayonnaise', 'Ketchup'];
        foreach ($sauces as $key => $name) {
            $sauce = new Sauce();
            $sauce->setName($name);
            $manager->persist($sauce);
            $this->addReference(self::SAUCE_REFERENCE . '_' . $key, $sauce);
        }

        // Images
        $images = ['burger1.jpg', 'burger2.jpg', 'burger3.jpg'];
        foreach ($images as $key => $name) {
            $image = new Image();
            $image->setName($name);
            $manager->persist($image);
            $this->addReference(self::IMAGE_REFERENCE . '_' . $key, $image);
        }

        // Burgers
        $burgers = [
            ['name' => 'Krabby Patty', 'price' => '4.99'],
            ['name' => 'Crabe Croustillant', 'price' => '5.49'],
            ['name' => 'Veggie Krab', 'price' => '4.49'],
        ];
        foreach ($burgers as $key => $data) {
            $burger = new Burger();
            $burger->setName($data['name']);
            $burger->setPrice($data['price']);
            $burger->setPain($this->getReference(self::PAIN_REFERENCE . '_' . $key, Pain::class));
            $burger->setImage($this->getReference(self::IMAGE_REFERENCE . '_' . $key, Image::class));
            $burger->addOignon($this->getReference(self::OIGNON_REFERENCE . '_' . ($key % 3), Oignon::class));
            $burger->addSauce($this->getReference(self::SAUCE_REFERENCE . '_' . ($key % 3), Sauce::class));
            $manager->persist($burger);
            $this->addReference(self::BURGER_REFERENCE . '_' . $key, $burger);
        }

        // Commentaires
        foreach ($burgers as $key => $data) {
            $commentaire = new Commentaire();
            $commentaire->setName('Délicieux burger #' . ($key + 1));
            $commentaire->setBurger($this->getReference(self::BURGER_REFERENCE . '_' . $key, Burger::class));
            $manager->persist($commentaire);
        }

        $manager->flush();
    }
}
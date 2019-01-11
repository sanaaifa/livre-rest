<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Livre;
use AppBundle\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
            $auteur = new Auteur();

            $auteur->setNomPrenom("test");
            $auteur->setEmail('test@email.com');

            $livre = new Livre();

            $livre->setTitre("test");
            $livre->setDescriptif("test");
            $livre->setISBN("test");
            $livre->setDateEdition(new \DateTime());
            $livre->setAuteur($auteur);



            $manager->persist($auteur);
            $manager->persist($livre);

        
        
        $manager->flush();

    }
}
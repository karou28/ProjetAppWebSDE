<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Compteur;

class CompteurFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=0;$i<10;$i++){

             $cpt = new Compteur();
             $cpt->setNumero ("cpt".$i) ;
             $manager->persist($cpt);
        }
       
        $manager->flush();
    }
}

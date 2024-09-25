<?php

namespace App\DataFixtures;

use App\Entity\Chimpokomon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        $chimpokoName = ["Chaussure", "SuperChimpokomon", "ChimpokoMegamon", "Jambon"];
        for ($i=0; $i < 100; $i++) { 
            $chimpokomon = new Chimpokomon();
            $chimpokomon->setName($chimpokoName[array_rand($chimpokoName)]);
            $chimpokomon->setStatus("on");
            $manager->persist($chimpokomon);
        }

        // $manager->persist($product);
        $manager->flush();
    }
}

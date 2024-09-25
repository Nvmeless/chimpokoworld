<?php

namespace App\DataFixtures;

use App\Entity\Chimpokodex;
use App\Entity\Chimpokomon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
class AppFixtures extends Fixture
{
    private Generator $faker;


    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }
    
    public function load(ObjectManager $manager): void
    {

        

        // $product = new Product();
        $chimpokoName = ["Chaussure", "SuperChimpokomon", "ChimpokoMegamon", "Jambon"];
        $chimpokodexEntries = [];
        $maxStat = 255;
        foreach ($chimpokoName as $key => $name) {
            $chimpokodexEntry = new Chimpokodex();

            $chimpokodexEntry->setName($name);
            $chimpokodexEntry->setStatus("on");
            $chimpokodexEntry->setIdDad($this->faker->numberBetween(1, 151));
            $chimpokodexEntry->setIdMom($this->faker->numberBetween(1, $maxStat));

            $pvMin = $this->faker->numberBetween(0, 151);
            $chimpokodexEntry->setPvMin( $pvMin);
            $chimpokodexEntry->setPvMax($this->faker->numberBetween( $pvMin, $maxStat));
            
            $manager->persist($chimpokodexEntry);
            $chimpokodexEntries[] = $chimpokodexEntry;
        }

        $manager->flush();

        for ($i=0; $i < 100; $i++) { 
            $chimpokodexEntry = $chimpokodexEntries[array_rand($chimpokodexEntries)];
            $chimpokomon = new Chimpokomon();
            $chimpokomon->setChimpokodex($chimpokodexEntry);
            
            $pvMax = $this->faker->numberBetween($chimpokodexEntry->getPvMin(), $chimpokodexEntry->getPvMax());
            $chimpokomon->setPvMax( $pvMax);
            $chimpokomon->setPv($this->faker->numberBetween( 0, $pvMax));

            $chimpokomon->setName($chimpokodexEntry->getName());

            $chimpokomon->setStatus("on");

            $manager->persist($chimpokomon);
        }

        // $manager->persist($product);
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Persona;
use App\Entity\Chimpokodex;
use App\Entity\Chimpokomon;
use App\Entity\Chimpokofood;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    private $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->userPasswordHasher = $userPasswordHasher;

    }

    public function load(ObjectManager $manager): void
    {

        $food = new Chimpokofood();
        $food->setName("Lembas");
        $food->setAmount(1000);
        $food->setStatus("on");

        $manager->persist($food);

        $food = new Chimpokofood();
        $food->setName("Karot");
        $food->setAmount(5);
        $food->setStatus("on");
        $manager->persist($food);

        $food = new Chimpokofood();
        $food->setName("Haricot Magique");
        $food->setAmount(350);
        $food->setStatus("on");
        $manager->persist($food);

        $manager->flush();
        $personas = [];
        $persona = new Persona();
        $persona->setGender($this->faker->randomElement(['male', 'female', "veau", "vache", 'cochon']));
        $persona->setBirthAt($this->faker->dateTimeThisCentury());
        $persona->setHeight($this->faker->numberBetween(100, 235));
        $persona->setCreatedAt(new \DateTime());
        $persona->setUpdatedAt(new \DateTime());
        $persona->setStatus('on');



        $publicUser = new User();
        $username = "admin";
        $password = "password";
        $publicUser->setUsername($username);
        $publicUser->setRoles(roles: ["ROLE_ADMIN"]);
        $publicUser->setName($this->faker->name());
        $publicUser->setSurname($this->faker->name());
        $publicUser->setPhone($this->faker->phoneNumber());
        $publicUser->setCreatedAt(new \DateTime());
        $publicUser->setUpdatedAt(new \DateTime());
        $publicUser->setStatus('on');
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, $password));
        $publicUser->setPersona($persona);
        $manager->persist($publicUser);
        $manager->persist($persona);

        $manager->flush();


        for ($i = 0; $i < 20; $i++) {
            $persona = new Persona();
            $persona->setGender($this->faker->randomElement(['male', 'female', "veau", "vache", 'cochon']));
            $persona->setBirthAt($this->faker->dateTimeThisCentury());
            $persona->setHeight($this->faker->numberBetween(100, 235));
            $persona->setCreatedAt(new \DateTime());
            $persona->setUpdatedAt(new \DateTime());
            $persona->setStatus('on');
            $personas[] = $persona;
        }




        foreach ($personas as $key => $value) {
            $publicUser = new User();
            $username = $this->faker->userName();
            $password = $this->faker->password();
            $publicUser->setUsername($username . "@" . $password);
            $publicUser->setRoles(["ROLE_PUBLIC"]);
            $publicUser->setName($this->faker->name());
            $publicUser->setSurname($this->faker->name());
            $publicUser->setPhone($this->faker->phoneNumber());
            $publicUser->setCreatedAt(new \DateTime());
            $publicUser->setUpdatedAt(new \DateTime());
            $publicUser->setStatus('on');
            $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, $password));
            $publicUser->setPersona($value);
            $manager->persist($publicUser);
            $manager->persist($value);

        }

        $manager->flush();
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
            $chimpokodexEntry->setPvMin($pvMin);
            $chimpokodexEntry->setPvMax($this->faker->numberBetween($pvMin, $maxStat));

            $manager->persist($chimpokodexEntry);
            $chimpokodexEntries[] = $chimpokodexEntry;
        }

        $manager->flush();

        for ($i = 0; $i < 100; $i++) {
            $chimpokodexEntry = $chimpokodexEntries[array_rand($chimpokodexEntries)];
            $chimpokomon = new Chimpokomon();
            $chimpokomon->setChimpokodex($chimpokodexEntry);

            $pvMax = $this->faker->numberBetween($chimpokodexEntry->getPvMin(), $chimpokodexEntry->getPvMax());
            $chimpokomon->setPvMax($pvMax);
            $chimpokomon->setPv($this->faker->numberBetween(0, $pvMax));

            $chimpokomon->setName($chimpokodexEntry->getName());

            $chimpokomon->setStatus("on");

            $manager->persist($chimpokomon);
        }

        // $manager->persist($product);
        $manager->flush();
    }
}

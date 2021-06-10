<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($u = 0; $u < 10; $u++) {
            $user = new User();

            $chrono = 1;

            $user->setNom($faker->firstName)
                ->setPrenom($faker->lastName)
                ->setEmail($faker->email)
                ->setPassword($this->passwordHasher->hashPassword(
                    $user,
                    'password'
                ));


            $manager->persist($user);

            for ($c = 0; $c < mt_rand(6, 25); $c++) {
                $client = new Client();
                $client->setNom($faker->firstName)
                    ->setPrenom($faker->lastName)
                    ->setEntreprise($faker->company)
                    ->setEmail($faker->email)
                    ->setUser($user);

                $manager->persist($client);

                for ($f = 0; $f < mt_rand(3, 10); $f++) {
                    $facture = new Facture();
                    $facture->setMontant($faker->randomFloat(2, 450, 10000))
                        ->setEnvoye($faker->dateTimeBetween('-3 months'))
                        ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELED']))
                        ->setClient($client)
                        ->setChrono($chrono);

                    $chrono++;

                    $manager->persist($facture);
                }
            }
        }

        $manager->flush();
    }
}

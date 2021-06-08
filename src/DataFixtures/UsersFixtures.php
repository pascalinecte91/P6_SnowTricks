<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture implements OrderedFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User(); //creation de mon user role ADMIN

        $user->setEmail($faker->email());
        $user->setPassword($this->encoder->encodePassword($user, 'bravo'));
        $user->setPasswordConfirm($this->encoder->encodePassword($user, 'bravo'));
        $user->setUsername($faker->lastName());
        $user->setRoles(['ROLE_ADMINISTRATOR']);
        $manager->persist($user);

        for ($i = 0; $i <= 5; $i++) {
            $user = new User();
            
            $user->setEmail($faker->email());
            $user->setPassword($this->encoder->encodePassword($user, 'toto'));
            $user->setPasswordConfirm($this->encoder->encodePassword($user, 'toto'));
            $user->setUsername($faker->lastName());
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);

            // enregistre l user dans une addReference
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}

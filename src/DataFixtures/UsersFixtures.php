<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
    $faker = Faker\Factory::create('fr_FR');

        for($nbUsers = 1; $nbUsers <5; $nbUsers++){
            $user = new User();
            $user->setEmail($faker->email());
            $user->setCreatedAt($faker->dateTime());
            $user->setPassword($this->encoder->encodePassword($user,'toto'));
            $user->setUsername($faker->lastName());
            $manager->persist($user);
            // enregistre l user dans une addReference
            $this->addReference('user_' . $nbUsers, $user);
}
        $manager->flush();
    }

}

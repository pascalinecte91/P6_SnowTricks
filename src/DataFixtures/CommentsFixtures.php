<?php

namespace App\DataFixtures;


use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class CommentsFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 4));
            $trick = $this->getReference('trick_' . $faker->numberBetween(1, 15));

            $comment = new Comment();

            $comment->setContent($faker->realText(250));
            $comment->setPseudo($faker->name());
            $comment->setCreatedAt($faker->dateTime());
            $comment->setRgpd(true);
            $comment->setEmail($faker->email());
            $comment->setUser($user);
            $comment->setTrick($trick);

            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}

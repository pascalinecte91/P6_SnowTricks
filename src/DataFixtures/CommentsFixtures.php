<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class CommentsFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->realText(250));
            $comment->setPseudo($faker->name());
            $comment->setCreatedAt($faker->dateTime());
            $manager->persist($comment);
        }
        $manager->flush();
    }
}


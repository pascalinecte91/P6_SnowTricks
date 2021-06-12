<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class CommentsFixtures extends Fixture implements OrderedFixtureInterface
{
    private $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }
   
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $tricks = $this->trickRepository->findAll();
        foreach ($tricks as $trick) {
            for ($i = 1; $i <= 11; $i++) {
                $user = $this->getReference('user_' . $faker->numberBetween(1, 4));

                $comment = new Comment();

                $comment->setContent($faker->realText(200));
                $comment->setPseudo($faker->name());
                $comment->setCreatedAt($faker->dateTime());
                $comment->setRgpd(true);
                $comment->setEmail($faker->email());
                $comment->setUser($user);
                $comment->setTrick($trick);
                $manager->persist($comment);
            }
        }
      
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 4;
    }
}

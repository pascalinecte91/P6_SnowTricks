<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
      /*  $img =$faker->image('public/uploads/picture');
       $pictureTrick = str_replace('public/uploads/picture\\', '', $img); */

        for ($nbTricks = 1; $nbTricks <=16; $nbTricks++){
            $category = $this->getReference('category_' . $faker->numberBetween(1, 8));

            $trick = new Trick;
            $trick->setName($faker->firstName())
                  ->setCategory($category)
                  ->setDescription($faker->realText(300))
                  ->setCreatedAt($faker->dateTimeBetween('- 1 years'))
                  ->setUpdateAt($faker->dateTimeInInterval('+5 days'));    
          

      /* for ($picture = 1; $picture <=2; $picture++){   
         $pictureTrick = new Picture(); 
         $pictureTrick->setName($pictureTrick);
           $trick->addPicture($pictureTrick);*/
        
        $manager->persist($trick);
       
    }

        $manager->flush();
} 

public function getDependencies()
{
    return[
        CategoriesFixtures::class,
    ];
}

}
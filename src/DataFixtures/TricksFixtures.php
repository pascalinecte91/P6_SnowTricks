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
       $img =$faker->image('public/uploads/picture');
       $pictureTrick = str_replace('public/uploads/picture\\', '', $img); 


       $listPictures = [
           'trick_1.jpg', 
           'trick_2.jpg', 
           'trick_3.jpg',
           'trick_4.jpg',
           'trick_5.jpg',
           'trick_6.jpg',
           'trick_7.jpg',
           'trick_8.jpg',
           'trick_9.jpg',
           'trick_10.jpg',
           'trick_11.jpg',
           'trick_12.jpg',
           'trick_13.jpg',
           'trick_14.jpg',
           'trick_15.jpg',
           'trick_16.jpg'

        ];
        
        $trickName = [
            1 => ['name' => 'Indy'],
            2 => ['name' => 'Seat belt'],
            3 => ['name' => 'Mute'],
            4 => ['name' => 'Japan'],
            5 => ['name' => 'Truck driver'],
            6 => ['name' => 'Style week'],
            7 => ['name' => '180'],
            8 => ['name' => '360'],
            9 => ['name' => 'Stalefish'],
            10 => ['name' => 'Nose grab'],
            11 => ['name' => 'Tail grab'],
            12 => ['name' => 'Big foot'],
            13 => ['name' => '900'],
            14 => ['name' => 'Backside air'],
            15 => ['name' => 'Old school'],
            16 => ['name' => 'Switch 270']
        ];
         

        for ($nbTricks = 1; $nbTricks <=16; $nbTricks++){
            $category = $this->getReference('category_' . $faker->numberBetween(1, 8));
            $user = $this->getReference('user_' . $faker->numberBetween(1, 4));

            foreach($trickName as $key => $value) {
            $trickName = new Trick();
            $trickName->setName($value['name'])
                  ->setCategory($category)
                  ->setUser($user)
                  ->setDescription($faker->realText(300))
                  ->setCreatedAt($faker->dateTimeBetween('- 1 years'))
                  ->setUpdateAt($faker->dateTimeInInterval('+5 days')) 
                  ->setPicture($faker->randomElement($listPictures));

          

       /*for ($picture = 1; $picture <=2; $picture++){   
         $pictureTrick = new Picture(); 
         $pictureTrick->setName($pictureTrick);
           $trick->addPicture($pictureTrick);*/
        
        $manager->persist($trickName);
       
    }

        $manager->flush();
} 
}
    
public function getDependencies()
{
    return[
        CategoriesFixtures::class,
        UsersFixtures::class,
    ];
}

}
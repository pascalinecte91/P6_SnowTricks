<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
       
        $listCategory = [
    1 => ['title' => 'grabs',  ],
    2 => ['title' => 'straight airs'],
    3 => ['title' => 'rotation'],
    4 => ['title' => 'spins'],
    5 => ['title' => 'flips and inverted rotations'],
    6 => ['title' => 'inverted hand plants'],
    7 => ['title' => 'stalls'],
    8 => ['title' => 'slides'],
        
 ];
  
        foreach($listCategory as $key => $value){
            $listCategory = new Category();
            $listCategory->setTitle($value['title']);
            $listCategory->setDescription(($faker->paragraph(2)));
            $manager->persist($listCategory);

            //enregistrer la categorie  dans une reference
            $this->addReference('category_' . $key, $listCategory);
        }
            
            $manager->flush();
    }
}


 

// $listCategory = [];
 /*for ($i=0; $i<7; $i++) {
            $category = new Category(); 
            $category->setDescription(($faker->paragraph(2)));
            $category->setTitle($faker->sentence());
            $listCategory[] = $category;
            $manager->persist($category);
        }*/

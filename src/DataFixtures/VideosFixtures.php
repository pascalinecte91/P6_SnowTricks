<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class VideosFixtures extends Fixture 
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
       
        $Category = ['grabs','straight airs','rotation','spins','flips and inverted rotations','inverted hand plants','stalls','slides'];

        $videos = [
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/0uGETVnkujA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/SFYYzy0UF-8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/monyw0mnLZg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/V9xuy-rVj9w" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/1CR0QmCaMTs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            ];    
         
        for ($i = 1; $i < 4; $i++){
        $video = new Video();
        $video->setUrl($videos[random_int(0, 4)]);
        $manager->persist($video);
    }    
        $manager->flush();
    }
}




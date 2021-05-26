<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Trick;

use App\Entity\Video;
use App\Entity\Picture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Service\UploaderFileServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class TricksFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var UploaderFileServiceInterface
     */
    private $uploaderFileService;
    private $containerBagInterface;

    public function __construct(UploaderFileServiceInterface $uploaderFileService, ContainerBagInterface $containerBagInterface)
    {
        $this->uploaderFileService = $uploaderFileService;
        $this->containerBagInterface = $containerBagInterface;
    }

    public function load(ObjectManager $manager)
    {
        $path = $this->containerBagInterface->get('directory_upload');
        $files = glob($path .'/*');
        foreach ($files as $file) {
            if (is_file($file)){
                unlink($file);
            }
        }
        
        $faker = Faker\Factory::create('fr_FR');

        $tricks = [
            ['name' => 'Indy'],
            ['name' => 'Seat belt'],
            ['name' => 'Mute'],
            ['name' => 'Japan'],
            ['name' => 'Truck driver'],
            ['name' => 'Style week'],
            ['name' => '180'],
            ['name' => '360'],
            ['name' => 'Stalefish'],
            ['name' => 'Nose grab'],
            ['name' => 'Tail grab'],
            ['name' => 'Big foot'],
            ['name' => '900'],
            ['name' => 'Backside air'],
            ['name' => 'Old school'],
            ['name' => 'Switch 270'],
        ];

        foreach ($tricks as $key => $trick) {
            $category = $this->getReference('category_' . $faker->numberBetween(1, 8));
            $user = $this->getReference('user_' . $faker->numberBetween(1, 4));

            $trickObject = new Trick();
            $trickObject->setName($trick['name']);
            $trickObject->setCategory($category);
            $trickObject->setUser($user);
            $trickObject->setDescription($faker->realText());
            $trickObject->setCreatedAt($faker->dateTimeBetween('- 1 years'));
            $trickObject->setUpdateAt($faker->dateTimeInInterval('+5 days'));

            $this->setReference('trick_' . $key, $trickObject);  

            for ($i = 1; $i <=3; $i++) {
                $asset = $this->getRandomFile($faker);

                $uploadedFile = new UploadedFile(__DIR__.'/images/'.$asset, $asset, null, null, true);
                $filename = $this->uploaderFileService->upload($uploadedFile);
                $picture = new Picture();
                $picture->setName($filename);
                $picture->setSubtitle($faker->sentence(6));
                $manager->persist($picture);
                $trickObject->addPicture($picture);
            }

            for ($i = 1; $i <=2; $i++){
                $video  = $this->getRandomVideos($faker);
                $videoObject = new Video();
                $videoObject->setUrl($video);
                $manager->persist($videoObject);
                $trickObject->addvideo($videoObject);
            }
            
            $manager->persist($trickObject);
        }

        $manager->flush();
    }



    public function getOrder()
    {
        return 3;
    }

    private function getRandomFile(Faker\Generator $faker): string
    {
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
            'trick_16.jpg',
        ];

        $asset = $faker->randomElement($listPictures);
        copy(__DIR__.'/images/'.$asset, __DIR__.'/images/copy-'.$asset);

        return 'copy-'.$asset;
    }

    private function getRandomVideos(Faker\Generator $faker): string
    {
        $videos = [
            'ieEF5QpInD4',
            '5zH-YEvyztA',
            '0uGETVnkujA',
            'V9xuy-rVj9w',
            
        ];

        return 'https://www.youtube.com/embed/' . $faker->randomElement($videos); 

    }
}

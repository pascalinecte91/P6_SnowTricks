<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Picture;
use App\Entity\Trick;
use App\Service\UploaderFileServiceInterface;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TricksFixtures extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var UploaderFileServiceInterface
     */
    private $uploaderFileService;

    public function __construct(UploaderFileServiceInterface $uploaderFileService)
    {
        $this->uploaderFileService = $uploaderFileService;
    }

    public function load(ObjectManager $manager)
    {
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
            $trickObject->setDescription($faker->realText(300));
            $trickObject->setCreatedAt($faker->dateTimeBetween('- 1 years'));
            $trickObject->setUpdateAt($faker->dateTimeInInterval('+5 days'));

            $this->setReference('trick_' . $key, $trickObject);  

            for ($i = 1; $i <=3; $i++) {
                $asset = $this->getRandomFile($faker);

                $uploadedFile = new UploadedFile(__DIR__.'/images/'.$asset, $asset, null, null, true);
                $filename = $this->uploaderFileService->upload($uploadedFile);

                $picture = new Picture();
                $picture->setName($filename);
                $picture->setSubtitle('description de la figure');

                $manager->persist($picture);

                $trickObject->addPicture($picture);
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
}

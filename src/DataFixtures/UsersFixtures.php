<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\Service\UploaderFileServiceInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class UsersFixtures extends Fixture implements OrderedFixtureInterface
{
    private $encoder;
    private $uploaderFileService;
    private $containerBagInterface;

    public function __construct(UserPasswordEncoderInterface $encoder, UploaderFileServiceInterface $uploaderFileService, ContainerBagInterface $containerBagInterface)
    {
        $this->encoder = $encoder;
        $this->uploaderFileService = $uploaderFileService;
        $this->containerBagInterface = $containerBagInterface;
    }

    public function load(ObjectManager $manager)
    {
        $path = $this->containerBagInterface->get('directory_upload');
        $files = glob($path . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        $faker = Faker\Factory::create('fr_FR');

        
         

        $user = new User(); //creation de mon user role ADMIN

        $user->setEmail($faker->email());
        $user->setPassword($this->encoder->encodePassword($user, 'bravo'));
        $user->setUsername($faker->lastName());
        $user->setRoles(['ROLE_ADMINISTRATOR']);
        $user->setAvatar($this->getRandomAvatar($faker));
        $manager->persist($user);

        for ($i = 0; $i <= 5; $i++) {
            $user = new User();
            
            $user->setEmail($faker->email());
            $user->setPassword($this->encoder->encodePassword($user, 'toto'));
            $user->setUsername($faker->lastName());

            $user->setAvatar($this->getRandomAvatar($faker));
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);



            // enregistre l user dans une addReference
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }

    private  function getRandomAvatar(Faker\Generator $faker): string
    {
        $listAvatars = [
            'avatar_1.jpg',
            'avatar_2.jpg',
            'avatar_3.jpg',
            'avatar_4.jpg',
            'avatar_5.jpg',
            'avatar_6.jpg',
            'avatar_7.jpg',
            'avatar_8.jpg',
            'avatar_9.jpg',
            'avatar_10.jpg',
        ];

        $avatar = $faker->randomElement($listAvatars);
        copy(__DIR__ . '/images/' . $avatar, __DIR__ . '/images/copy-' . $avatar);
        $newAvatarFileName = 'copy-' .$avatar;
        $uploadedFile = new UploadedFile(__DIR__ . '/images/' . $newAvatarFileName, $newAvatarFileName, null, null, true);
        
        return $this->uploaderFileService->upload($uploadedFile);
    }

    public function getOrder()
    {
        return 1;
    }
}

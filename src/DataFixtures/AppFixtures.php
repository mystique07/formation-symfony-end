<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public  function load(ObjectManager $manager)
    {
        $this->loadData($manager);

    }

    public function loadData(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        //Nous gerons des Utilisatuers
        $users= [];
        $genres =['male', 'female'];
        for ($i = 1; $i <=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture ='https://randomuser.me/api/portraits/';
            $picture_id = $faker->numberBetween(1, 99). '.jpg';

             $picture .= ($genre === 'male' ? 'men/' : 'women') . $picture_id;
            $hash = $this->encoder->encodePassword($user, 'password');
            try {
                $user->setFirstName($faker->firstName($genre))
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setIntroduction($faker->sentence())
                    ->setDescription($faker->realText(random_int(200, 500)))
                    ->setPicture($picture)
                    ->setHash($hash);
            } catch (\Exception $e) {
                echo "Erreurs $e";
            }
            $manager->persist($user);
            $manager->flush();
            $users[] =$user;


        }

        //Nous gérons les annonces
        for ($i=0; $i<30; $i++){
            $ad = new Ad();
            $title = $faker->sentence();
            $image = $faker->imageUrl(1000,350);
            $introduction=$faker->paragraph(2);
            $content = $faker->realText(random_int(300, 500));
            $user = $users[random_int(0, count($users)-1)];


            try {
                $ad->setTitle($title)
                    ->setCoverImage($image)
                    ->setIntroduction($introduction)
                    ->setContent($content)
                    ->setPrice(random_int(40, 200))
                    ->setRooms(random_int(1, 5))
                    ->setAuthor($user)
                ;
            } catch (\Exception $e) {
                echo "<div class='alert alert-warning'>$e</div>";
            }


            try {
                for ($j = 0, $nb = random_int(1, 6); $j < $nb; $j++) {
                    $image = new Image();
                    $image->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);
                    $manager->persist($image);

                }
            } catch (\Exception $e) {
                echo "Error is $e";
            }
            $manager->persist($ad);
        }
        $manager->flush();


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

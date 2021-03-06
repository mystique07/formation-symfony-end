<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser = new User();
        $adminUser->setFirstName('eldy')
            ->setLastName('mina')
            ->setEmail('mina@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture('https://randomuser.me/api/portraits/men/53.jpg')
            ->setIntroduction($faker->sentence())
            ->setDescription($faker->realText(600))
            ->addUserRole($adminRole);
        $manager->persist($adminUser);

        //Nous gerons des Utilisatuers
        $users= [];
        $genres =['male', 'female'];
        for ($i = 1; $i <=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture ='https://randomuser.me/api/portraits/';
            $picture_id = $faker->numberBetween(1, 99). '.jpg';

             $picture .= ($genre === 'male' ? 'men/'  : 'women/') . $picture_id;
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
                echo 'Erreurs'. $e;
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
            //Gestion de reservations
            for ($j= 1, $jMax = random_int(0, 10); $j<= $jMax; $j++){
                $booking =new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                //gestion de la date de fin
                $duration = random_int(3, 10);
                $endDate  = (clone $startDate)->modify("+ $duration days");
                $amount   = $ad->getPrice() * $duration;

                $booker = $users[random_int(0, count($users)-1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);


                $manager->persist($booking);

                //Gestion des commenntaire
                if (random_int(0,1)){
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph)
                        ->setRating(random_int(1, 5))
                        ->setAuthor($booker)
                        ->setAd($ad);

                    $manager->persist($comment);
                }

            }
            $manager->persist($ad);
        }
        $manager->flush();
    }
}

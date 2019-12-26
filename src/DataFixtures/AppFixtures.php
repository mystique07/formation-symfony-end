<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public  function load(ObjectManager $manager)
    {
        $this->loadData($manager);

    }

    public function loadData(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');


        for ($i=0; $i<30; $i++){
            $ad = new Ad();
            $title = $faker->sentence();
            $image = $faker->imageUrl(1000,350);
            $introduction=$faker->paragraph(2);

            try {
                $content = $faker->realText(random_int(200, 225));
            } catch (\Exception $e) {
                echo  "Error content ".$e;
            }

            try {
                $ad->setTitle($title)
                    ->setCoverImage($image)
                    ->setIntroduction($introduction)
                    ->setContent($content)
                    ->setPrice(random_int(40, 200))
                    ->setRooms(random_int(1, 5));
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

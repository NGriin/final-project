<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    private static $names = [
        'Asus ROG Strix GeForce RTX 3050 OC Edition',
        'Gigabyte Radeon RX 6600 XT Eagle PCI-E',
        'Sapphire Nitro+ Radeon RX 6700 XT',
        'MSI GeForce RTX 3070 Ti Gaming X Trio',
        'Gigabyte Radeon RX 6900 XT Gaming OC'
    ];
    private static $countries = [
        'China',
        'Thailand'
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            $product = new Product();
            $product
                ->setName(self::$names[$i])
                ->setDescription($faker->text)
                ->setCreatorCountry($faker->randomElement(self::$countries))
                ->setIsActive(rand(0, 1));

            /*$manager->persist($product);

            $manager->flush();*/

            $image = (new ProductImage())
                ->setImageFilename('videoca.png')
                ->setProduct($product);

            $manager->persist($image);
            $image = (new ProductImage())
                ->setImageFilename('card.jpg')
                ->setProduct($product);

            $manager->persist($image);
            $image = (new ProductImage())
                ->setImageFilename('slider.png')
                ->setProduct($product);

            $manager->persist($image);
            $image = (new ProductImage())
                ->setImageFilename('bigGoods.png')
                ->setProduct($product);

         /*   $manager->persist($image);*/
        }
    }
}

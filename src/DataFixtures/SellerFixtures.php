<?php

namespace App\DataFixtures;


use App\Entity\Seller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class SellerFixtures extends Fixture
{
    private static $shops = [
        'Технопарк',
        'DNS',
        'Ситилинк',
        'М.Видео',
        'Эльдорадо',
    ];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            $seller = new Seller();

            $seller
                ->setEmail($faker->email)
                ->setPhone($faker->phoneNumber)
                ->setTitle(self::$shops[$i])
                ->setDescription($faker->paragraph)
                ->setAddress($faker->address)
                ->setImageFilename('bigGoods.png');

           /* $manager->persist($seller);

            $manager->flush();*/
        }
    }
}

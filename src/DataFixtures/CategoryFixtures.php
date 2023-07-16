<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    private static $categoryTitles = [
        'Electronics',
        'Bags',
        'Video cards',
        'Fashion',
        'Mobiles',
        'Furniture',
        'Clothing',
        'Accessories',
        'Trends',
        'More'
    ];
    private static $IconFilenames = [
        '1.svg',
        '2.svg',
        '3.svg',
        '4.svg',
        '5.svg',
        '6.svg',
        '7.svg',
        '8.svg',
        '9.svg',
        '10.svg',
    ];

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            $category
                ->setTitle(self::$categoryTitles[$i])
                ->setIconFilename(self::$IconFilenames[$i])
                ->setSortIndex($i)
                ->setIsActive(rand(null, 1))
                ->setParentId(0);

            $manager->persist($category);

            $manager->flush();
        }
    }
}

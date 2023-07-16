<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Features;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\ProductSeller;
use App\Entity\Review;
use App\Entity\Seller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductSellerFixtures extends Fixture
{
    private static $names = [
        'Asus ROG Strix GeForce RTX 3050 OC Edition',
        'Gigabyte Radeon RX 6600 XT Eagle PCI-E',
        'Sapphire Nitro+ Radeon RX 6700 XT',
        'MSI GeForce RTX 3070 Ti Gaming X Trio',
        'Gigabyte Radeon RX 6900 XT Gaming OC',
        'INNO3D GeForce RTX 3060 TWIN X2 OC',
        'ASUS GeForce RTX 3060 Ti Dual MINI V2',
        'ASRock AMD Radeon RX 6700 XT Challenger D OC',
        'Powercolor AMD Radeon RX 6600 XT Fighter',
        'Gigabyte Radeon RX 6500 XT Gaming OC'
    ];
    private static $countries = [
        'China',
        'Thailand'
    ];

    private static $shops = [
        'Технопарк',
        'DNS',
        'Ситилинк',
        'М.Видео',
        'Эльдорадо',
        'Магазин1',
        'Магазин2',
        'Магазин3',
        'Магазин4',
        'Магазин5'
    ];

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

    private $memory = [
        '4Гб',
        '8Гб',
        '16Гб',
        'МногоГб',
    ];

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $savedFeatures = [];
        foreach ($this->memory as $memory) {
            $savedFeatures[] = $feature = (new Features())
                ->setTitle('Оперативная память')
                ->setValue($memory);
            $manager->persist($feature);
        }
        $manager->flush();

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


            $manager->persist($feature);

            $product = new Product();
            $product
                ->setName(self::$names[$i])
                ->setDescription($faker->text)
                ->setCreatorCountry($faker->randomElement(self::$countries))
                ->setIsActive(1)
                ->setSortIndex($i)
                ->setCategory($category)
                ->addFeature($faker->randomElement($savedFeatures));

            $manager->persist($product);

            $manager->flush();

            $seller = new Seller();

            $seller
                ->setEmail($faker->email)
                ->setPhone($faker->phoneNumber)
                ->setTitle(self::$shops[$i])
                ->setDescription($faker->paragraph)
                ->setAddress($faker->address)
                ->setImageFilename('bigGoods.png');

            $manager->persist($seller);

            $manager->flush();

            $review = (new Review())
                ->setAuthorName('Кто-то')
                ->setText($faker->paragraph)
                ->setEmail($faker->email)
                ->setProduct($product);
            $manager->persist($review);

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

            $manager->persist($image);

            $productSeller = (new ProductSeller($product, $seller))
                ->setCost(rand(200, 300))
                ->setCostDiscount(rand(40, 150));
            $manager->persist($productSeller);

        }
        $product = new Product();
        $product
            ->setName(self::$names[1])
            ->setDescription($faker->text)
            ->setCreatorCountry($faker->randomElement(self::$countries))
            ->setIsActive(0)
            ->setCategory($category);

//        $product->addFeature($feature);

        $manager->persist($product);

        $manager->flush();
    }
}

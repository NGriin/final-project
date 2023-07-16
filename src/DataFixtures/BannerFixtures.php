<?php

namespace App\DataFixtures;

use App\Entity\Banner;
use Doctrine\Persistence\ObjectManager;

class BannerFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(Banner::class, 10, function (Banner $banner){
            $banner
                ->setIsActive($this->faker->numberBetween(0,1))
                ->setImageFilename('slider.png')
                ->setDescription($this->faker->paragraph)
                ->setTitle(mb_substr($this->faker->paragraph,0,35));
        });
    }
}

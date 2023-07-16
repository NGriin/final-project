<?php

namespace App\DataFixtures;

use App\Entity\Features;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FeaturesFixtures extends Features
{
    private $memory = [
        '4Гб',
        '8Гб',
        '16Гб',
        'МногоГб',
        ];
    public function load(ObjectManager $manager): void
    {
        $feature = (new Features())
            ->setTitle('Оперативная память')
            ->setValue('4Гб');
        $manager->persist($feature);
        $feature = (new Features())
            ->setTitle('Оперативная память')
            ->setValue('8Гб');
        $manager->persist($feature);
        $feature = (new Features())
            ->setTitle('Оперативная память')
            ->setValue('16Гб');
        $manager->persist($feature);
        $feature = (new Features())
            ->setTitle('Оперативная память')
            ->setValue('МногоГб');
        $manager->persist($feature);

        $manager->flush();
    }
}

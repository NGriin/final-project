<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Symfony\Config\TwigConfig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;


class MyTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('shuffle', [$this, 'shuffleFilter']),
            new TwigFilter('categories', [$this, 'setGlobalVar'])
        ];
    }

    public function shuffleFilter($array)
    {
        shuffle($array);
        return $array;
    }
}
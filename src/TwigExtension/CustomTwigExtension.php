<?php
namespace App\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomTwigExtension extends AbstractExtension{

    public function getFilters()
    {
        return[
            new TwigFilter('image', [$this, 'defaultImage'])
        ];
    }

    public function defaultImage(string $path): string{
        if(strlen(trim($path))==0){
            return 'default.png';
        }
        return $path;
    }
}
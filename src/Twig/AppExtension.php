<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('price', [$this, 'price']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            //new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    /**
     * filtre pour formater les prix
     *
     * @param float $price
     * @param string|null $currency
     * @param integer|null $decimals
     * @return string
     */
    public function price(float $price, ?string $currency = 'KMF', ?int $decimals = 0): string
    {
        return number_format($price, $decimals, ',', ' '). ' '.$currency;
    }
}

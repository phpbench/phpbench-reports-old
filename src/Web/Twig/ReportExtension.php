<?php

namespace Phpbench\Reports\Twig;

use Twig\ExtensionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Aura\Router\Generator;
use Twig\TwigFilter;

class ReportExtension extends AbstractExtension
{
    /**
     * @var Generator
     */
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path', function ($name, array $params = []) {
                return $this->generator->generate($name, $params);
            }),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('values', function (array $array) {
                return array_values($array);
            }),
            new TwigFilter('round', function (float $value = null, int $precision) {
                return number_format($value, $precision);
            }),
            new TwigFilter('numeric_keys', function (array $values = []) {
                return array_map(function ($key) {
                    return (int) $key;
                }, array_keys($values));
            })
        ];
    }
}

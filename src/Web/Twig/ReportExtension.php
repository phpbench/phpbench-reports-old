<?php

namespace Phpbench\Reports\Twig;

use Twig\ExtensionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Aura\Router\Generator;

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
            new TwigFunction('path', function ($name, array $params) {
                return $this->generator->generate($name, $params);
            }),
        ];
    }
}

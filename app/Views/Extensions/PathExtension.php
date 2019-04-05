<?php

namespace App\Views\Extensions;

use League\Route\RouteCollection;
use Twig_Extension;
use Twig_SimpleFunction;

class PathExtension extends Twig_Extension
{
    protected $route;

    /**
     * PathExtension constructor.
     * @param RouteCollection $route
     */
    public function __construct(RouteCollection $route)
    {
        $this->route = $route;
    }

    /**
     * @return array|\Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('route', [$this, 'route'])

        ];
    }

    /**
     * @param $name
     * @return string
     */
    public function route($name)
    {
        return $this->route->getNamedRoute($name)->getPath();
    }
}

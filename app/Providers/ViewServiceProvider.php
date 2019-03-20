<?php

namespace App\Providers;

use App\Views\View;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Twig_Environment;
use Twig_Loader_Filesystem;

class ViewServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        View::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $config = $container->get('config');

        $container->share(View::class, function () use ($config) {
            $loader = new Twig_Loader_Filesystem(base_path('views'));

            $twig = new Twig_Environment($loader, [
                'cache' => $config->get('cache.views.path'),
                'debug' => $config->get('app.debug')
            ]);

            if ($config->get('app.debug')) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }

            return new View($twig);
        });
    }
}

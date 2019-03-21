<?php

namespace App\Providers;

use App\Config\Config;
use App\Config\Loaders\ArrayLoader;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'config'
    ];

    public function register()
    {
        $this->getContainer()->share('config', function () {
            $loader = new ArrayLoader([
                'app' => base_path('config/app.php'),
                'cache' => base_path('config/cache.php'),
                'database' => base_path('config/database.php')
            ]);

            return (new Config)->load([$loader]);
        });
    }
}

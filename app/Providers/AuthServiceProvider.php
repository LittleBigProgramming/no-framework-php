<?php

namespace App\Providers;

use App\Auth\Auth;
use App\Auth\Hashing\HashingInterface;
use App\Session\SessionStore;
use Doctrine\ORM\EntityManager;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AuthServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        Auth::class
    ];


    public function register()
    {
        $container = $this->getContainer();

        $container->share(Auth::class, function () use ($container) {
            return new Auth(
                $container->get(EntityManager::class),
                $container->get(HashingInterface::class),
                $container->get(SessionStore::class)
            );
        });
    }
}

<?php

namespace App\Providers;

use App\Auth\Hashing\BcryptHasher;
use App\Auth\Hashing\HashingInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class HashServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        HashingInterface::class
    ];

    public function register()
    {
        $this->getContainer()->share(HashingInterface::class, function () {
            return new BcryptHasher();
        });
    }
}

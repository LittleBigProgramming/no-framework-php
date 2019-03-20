<?php

namespace App\Config\Loaders;

use App\Config\Loaders\Loader;
use Exception;

class ArrayLoader implements Loader
{
    protected $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function parse()
    {
        $parsed = [];

        foreach ($this->files as $namespace => $path) {
            try {
                $parsed[$namespace] = require $path;
            } catch (Exception $e) {
                echo $e;
            }
        }

        return $parsed;
    }
}

<?php

namespace App\Config;

use App\Config\Loaders\Loader;

class Config
{
    protected $config = [];
    protected $cache = [];
    /**
     * @param array $loaders
     * @return $this
     */
    public function load(array $loaders)
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof Loader) {
                continue;
            }

            $this->config = array_merge($this->config, $loader->parse());
        }

        return $this;
    }

    /**
     * @param $key
     * @return array|mixed
     */
    public function get($key, $default = null)
    {
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        return $this->addToCache($key, $this->extractFromConfig($key) ?? $default);
    }

    /**
     * @param $key
     * @return bool
     */
    protected function existsInCache($key)
    {
        return isset($this->cache[$key]);
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function fromCache($key)
    {
        return $this->cache[$key];
    }

    /**
     * @param $key
     * @return array|mixed|void
     */
    protected function extractFromConfig($key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if ($this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }

            return;
        }

        return $filtered;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function addToCache($key, $value)
    {
        $this->cache[$key] = $value;

        return $value;
    }

    /**
     * @param array $config
     * @param $key
     * @return bool
     */
    protected function exists(array $config, $key)
    {
        return array_key_exists($key, $config);
    }
}

<?php

namespace App\Cookie;

class CookieJar
{
    protected $path = '/';
    protected $domain = null;
    protected $secure = false;
    protected $httpOnly = true;

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function get($key, $default = null)
    {
        if ($this->exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    /**
     * @param $name
     * @param $value
     * @param int $minutes
     */
    public function set($name, $value, $minutes = 60)
    {
        $expiry = time() + ($minutes * 60);

        setcookie(
            $name,
            $value,
            $expiry,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httpOnly
        );
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return isset($_COOKIE[$key]) && !empty($_COOKIE[$key]);
    }

    /**
     * Sets the cookie to null and makes it expire due to negative timeframe
     *
     * @param $key
     */
    public function clear($key)
    {
        $this->set($key, null, -2628000, $this->path, $this->domain);
    }

    /**
     * @param $key
     * @param $value
     */
    public function forever($key, $value)
    {
        $this->set($key, $value, 2628000);
    }
}

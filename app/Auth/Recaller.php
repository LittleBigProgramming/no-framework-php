<?php

namespace App\Auth;

class Recaller
{
    protected $seperator = '|';
    /**
     * @return array
     * @throws \Exception
     */
    public function generate()
    {
        return [$this->generateIdentifier(), $this->generateToken()];
    }

    /**
     * @param $identifier
     * @param $token
     * @return string
     */
    public function generateValueForCookie($identifier, $token)
    {
        return $identifier . $this->seperator . $token;
    }

    /**
     * @param $token
     * @return string
     */
    public function getTokenHashForDatabase($token)
    {
        return hash('sha256', $token);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function generateIdentifier()
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function generateToken()
    {
        return bin2hex(random_bytes(64));
    }
}

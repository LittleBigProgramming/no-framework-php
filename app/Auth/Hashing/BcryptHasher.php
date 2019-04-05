<?php

namespace App\Auth\Hashing;

use http\Exception\RuntimeException;

class BcryptHasher implements HashingInterface
{
    /**
     * @param $password
     * @return bool|string
     */
    public function create($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, $this->options());

        if (!$hash) {
            throw new RuntimeException('Bcrypt not supported');
        }

        return $hash;
    }

    /**
     * @param $password
     * @param $hash
     * @return bool
     */
    public function check($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * @param $hash
     * @return bool
     */
    public function needsRehash($hash)
    {
        return password_needs_rehash($hash, PASSWORD_BCRYPT, $this->options());
    }

    /**
     * @return array
     */
    protected function options() : array
    {
        return [
            'cost' => 12
        ];
    }
}

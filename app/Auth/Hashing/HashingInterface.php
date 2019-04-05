<?php

namespace App\Auth\Hashing;

interface HashingInterface
{
    public function create($password);

    public function check($password, $hash);

    public function needsRehash($hash);
}

<?php

namespace App\Auth\Providers;

use App\Models\User;
use Doctrine\ORM\EntityManager;

class DatabaseProvider implements UserProvider
{
    protected $database;

    public function __construct(EntityManager $database)
    {
        $this->database = $database;
    }

    /**
     * @param $username
     * @return null|object
     */
    public function getByUsername($username)
    {
        return $this->database->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getById($id)
    {
        return $this->database->getRepository(User::class)->find($id);
    }

    public function updateUserPasswordHash($id, $hash)
    {
        $this->database->getRepository(User::class)->find($id)->update([
            'password' => $hash
        ]);

        $this->database->flush();
    }

    public function getUserByRememberIdentifier($identifier)
    {
        return $this->database->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier
        ]);
    }

    public function clearUserByRememberToken($id)
    {
        $this->database->getRepository(User::class)->find($id)->update([
            'remember_identifier' => null,
            'remember_token' => null
        ]);

        $this->database->flush();
    }

    public function setUserRememberToken($id, $identifier, $hash)
    {
        $this->database->getRepository(User::class)->find($id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $hash
        ]);

        $this->database->flush();
    }
}

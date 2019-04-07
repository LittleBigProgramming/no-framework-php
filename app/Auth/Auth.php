<?php

namespace App\Auth;

use App\Auth\Hashing\HashingInterface;
use App\Models\User;
use App\Session\SessionStore;
use Doctrine\ORM\EntityManager;

class Auth
{
    protected $database;
    protected $hash;
    protected $session;

    public function __construct(EntityManager $database, HashingInterface $hash, SessionStore $session)
    {
        $this->database = $database;
        $this->hash = $hash;
        $this->session = $session;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function attempt($username, $password)
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        $this->setUserSession($user);
        return true;
    }

    /**
     * @param $username
     * @return null|object
     */
    protected function getByUsername($username)
    {
        return $this->database->getRepository(User::class)->findOneBy([
            'email' => $username
        ]);
    }

    /**
     * @param $user
     * @param $password
     * @return mixed
     */
    protected function hasValidCredentials($user, $password)
    {
        return $this->hash->check($password, $user->password);
    }

    /**
     * @param $user
     */
    protected function setUserSession($user)
    {
        $this->session->set('id', $user->id);
    }
}

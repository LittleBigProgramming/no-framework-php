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
    protected $user;

    /**
     * Auth constructor.
     * @param EntityManager $database
     * @param HashingInterface $hash
     * @param SessionStore $session
     */
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

        if ($this->needsRehash($user)) {
            $this->rehashPassword($user, $password);
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
     * @param $id
     * @return null|object
     */
    protected function getById($id)
    {
        return $this->database->getRepository(User::class)->find($id);
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
        $this->session->set($this->key(), $user->id);
    }

    protected function needsRehash($user)
    {
        return $this->hash->needsRehash($user->password);
    }

    protected function rehashPassword($user, $password)
    {
        $this->database->getRepository(User::class)->find($user->id)->update([
            'password' => $this->hash->create($password)
        ]);

        $this->database->flush();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    protected function key()
    {
        return 'id';
    }

    /**
     * @return mixed
     */
    public function hasUserInSession()
    {
        return $this->session->exists($this->key());
    }

    /**
     * @throws \Exception
     */
    public function setUserFromSession()
    {
        $user = $this->getById($this->session->get($this->key()));

        if (!$user) {
            throw new \Exception();
        }

        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function isLoggedIn()
    {
        return $this->hasUserInSession();
    }
}

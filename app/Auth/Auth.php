<?php

namespace App\Auth;

use App\Auth\Hashing\HashingInterface;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\SessionStore;
use Doctrine\ORM\EntityManager;

class Auth
{
    protected $database;
    protected $hash;
    protected $session;
    protected $recaller;
    protected $cookie;
    protected $user;

    /**
     * Auth constructor.
     * @param EntityManager $database
     * @param HashingInterface $hash
     * @param SessionStore $session
     * @param Recaller $recaller
     * @param CookieJar $cookie
     */
    public function __construct(
        EntityManager $database,
        HashingInterface $hash,
        SessionStore $session,
        Recaller $recaller,
        CookieJar $cookie
    ) {
        $this->database = $database;
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
    }

    /**
     * @param $username
     * @param $password
     * @param bool $remember
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function attempt($username, $password, $remember = false)
    {
        $user = $this->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->rehashPassword($user, $password);
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }

    public function logout()
    {
        $this->session->clear($this->key());
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

    /**
     * @param $user
     * @return mixed
     */
    protected function needsRehash($user)
    {
        return $this->hash->needsRehash($user->password);
    }

    /**
     * @param $user
     * @param $password
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setUserFromCookie()
    {
        list($identifier, $token) = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        $user = $this->database->getRepository(User::class)->findOneBy([
            'remember_identifier' => $identifier
        ]);

        if (!$user) {
            $this->cookie->clear('remember');

            return;
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $user = $this->database->getRepository(User::class)->find($user->id)->update([
                'remember_identifier' => null,
                'remember_token' => null
            ]);

            $this->database->flush();

            throw new \Exception();
        }

        $this->setUserSession($user);
    }

    /**
     * @return bool
     */
    public function hasRecallerCookie()
    {
        return $this->cookie->exists('remember');
    }

    /**
     * @param $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function setRememberToken($user)
    {
        list($identifier, $token) = $this->recaller->generate();

        $this->cookie->set('remember', $this->recaller->generateValueForCookie($identifier, $token));

        $this->database->getRepository(User::class)->find($user->id)->update([
            'remember_identifier' => $identifier,
            'remember_token' => $this->recaller->getTokenHashForDatabase($token),
        ]);

        $this->database->flush();
    }

    /**
     * @return mixed
     */
    public function isLoggedIn()
    {
        return $this->hasUserInSession();
    }
}

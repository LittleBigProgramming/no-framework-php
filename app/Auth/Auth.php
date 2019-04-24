<?php

namespace App\Auth;

use App\Auth\Hashing\HashingInterface;
use App\Auth\Providers\UserProvider;
use App\Cookie\CookieJar;
use App\Session\SessionStore;

class Auth
{
    protected $hash;
    protected $session;
    protected $recaller;
    protected $cookie;
    protected $user;
    protected $provider;

    /**
     * Auth constructor.
     * @param HashingInterface $hash
     * @param SessionStore $session
     * @param Recaller $recaller
     * @param CookieJar $cookie
     * @param UserProvider $provider
     */
    public function __construct(
        HashingInterface $hash,
        SessionStore $session,
        Recaller $recaller,
        CookieJar $cookie,
        UserProvider $provider
    ) {
        $this->hash = $hash;
        $this->session = $session;
        $this->recaller = $recaller;
        $this->cookie = $cookie;
        $this->provider = $provider;
    }

    /**
     * @param $username
     * @param $password
     * @param bool $remember
     * @return bool
     * @throws \Exception
     */
    public function attempt($username, $password, $remember = false)
    {
        $user = $this->provider->getByUsername($username);

        if (!$user || !$this->hasValidCredentials($user, $password)) {
            return false;
        }

        if ($this->needsRehash($user)) {
            $this->provider->updateUserPasswordHash($user->id, $this->hash->create($password));
        }

        $this->setUserSession($user);

        if ($remember) {
            $this->setRememberToken($user);
        }

        return true;
    }


    public function logout()
    {
        $this->provider->clearUserByRememberToken($this->user->id);
        $this->cookie->clear('remember');
        $this->session->clear($this->key());
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
        $user = $this->provider->getById($this->session->get($this->key()));

        if (!$user) {
            throw new \Exception();
        }

        $this->user = $user;
    }

    /**
     * @throws \Exception
     */
    public function setUserFromCookie()
    {
        list($identifier, $token) = $this->recaller->splitCookieValue(
            $this->cookie->get('remember')
        );

        if (!$user = $this->provider->getUserByRememberIdentifier($identifier)) {
            $this->cookie->clear('remember');

            return;
        }

        if (!$this->recaller->validateToken($token, $user->remember_token)) {
            $this->provider->clearUserByRememberToken($user->id);
            $this->cookie->clear('remember');

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
     * @throws \Exception
     */
    protected function setRememberToken($user)
    {
        list($identifier, $token) = $this->recaller->generate();

        $this->cookie->set('remember', $this->recaller->generateValueForCookie($identifier, $token));

        $this->provider->setUserRememberToken($user->id, $identifier, $this->recaller->getTokenHashForDatabase($token));
    }

    /**
     * @return mixed
     */
    public function isLoggedIn()
    {
        return $this->hasUserInSession();
    }
}

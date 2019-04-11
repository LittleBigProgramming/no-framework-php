<?php

namespace App\Security;

use App\Session\SessionStore;

class Csrf
{
    protected $session;
    protected $persistToken = true;

    /**
     * Csrf constructor.
     * @param SessionStore $session
     */
    public function __construct(SessionStore $session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function key()
    {
        return '_token';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function token()
    {
        if (!$this->tokenNeedsToBeGenerated()) {
            return $this->getTokenFromSession();
        }
        $this->session->set(
            $this->key(),
            $token = bin2hex(random_bytes(32))
        );

        return $token;
    }

    /**
     * @return mixed
     */
    protected function getTokenFromSession()
    {
        return $this->session->get($this->key());
    }

    /**
     * @return bool
     */
    protected function tokenNeedsToBeGenerated()
    {
        if (!$this->session->exists(($this->key()))) {
            return true;
        }

        if ($this->shouldPersistToken()) {
            return false;
        }

        return $this->session->exists($this->key());
    }

    /**
     * @return bool
     */
    protected function shouldPersistToken()
    {
        return $this->persistToken;
    }
}

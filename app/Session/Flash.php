<?php

namespace App\Session;

class Flash
{
    protected $session;
    protected $messages;

    public function __construct(SessionStore $session)
    {
        $this->session = $session;
        $this->loadMessagesIntoCache();

        $this->clear();
    }

    public function has($key)
    {
        return isset($this->messages[$key]);
    }

    public function get($key)
    {
        return $this->has($key) ? $this->messages[$key] : null;
    }

    public function now($key, $value)
    {
        $this->session->set('flash', array_merge(
            $this->session->get('flash') ?? [],
            [$key => $value]
        ));
    }

    protected function getAll()
    {
        return $this->session->get('flash');
    }

    protected function loadMessagesIntoCache()
    {
        $this->messages = $this->getAll();
    }

    protected function clear()
    {
        $this->session->clear('flash');
    }
}

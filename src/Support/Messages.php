<?php namespace Orchestra\Support;

use Closure;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\MessageBag as M;

class Messages extends M
{
    /**
     * The session store instance.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * Cached messages to be extends to current request.
     *
     * @var Messages
     */
    protected $instance;

    /**
     * Set the session store.
     *
     * @param  \Illuminate\Session\Store   $session
     * @return Messages
     */
    public function setSessionStore(SessionStore $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get the session store.
     *
     * @return \Illuminate\Session\Store
     */
    public function getSessionStore()
    {
        return $this->session;
    }

    /**
     * Retrieve Message instance from Session, the data should be in
     * serialize, so we need to unserialize it first.
     *
     * @return Messages
     */
    public function retrieve()
    {
        $messages = null;

        if (! isset($this->instance)) {
            $this->instance = new static;
            $this->instance->setSessionStore($this->session);

            if ($this->session->has('message')) {
                $messages = @unserialize($this->session->get('message', ''));
            }

            $this->session->forget('message');

            if (is_array($messages)) {
                $this->instance->merge($messages);
            }
        }

        return $this->instance;
    }

    /**
     * Extend Messages instance from session.
     *
     * @param  \Closure $callback
     * @return Messages
     */
    public function extend(Closure $callback)
    {
        $instance = $this->retrieve();
        call_user_func($callback, $instance);

        return $instance;
    }

    /**
     * Store current instance.
     *
     * @return void
     */
    public function save()
    {
        $this->session->flash('message', $this->serialize());
    }

    /**
     * Compile the instance into serialize.
     *
     * @return string   serialize of this instance
     */
    public function serialize()
    {
        return serialize($this->messages);
    }
}

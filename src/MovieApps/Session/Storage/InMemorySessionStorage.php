<?php

namespace MovieApps\Session\Storage;

use EbysSdk\Storage\StorageInterface;
use MovieApps\Session\Session;

class InMemorySessionStorage implements StorageInterface
{
    /**
     * @var array
     */
    private $sessions;

    /**
     * @param $key
     * @return Session
     */
    public function get($key)
    {
        if (!isset($this->sessions[$key])) {
            return null;
        }

        return $this->sessions[$key];
    }

    /**
     * @param $key
     * @param Session $session
     * @return mixed
     */
    public function set($key, $session)
    {
        $this->sessions[$key] = $session;
    }

    /**
     * Removes a key from the storage interface.
     *
     * @param mixed $key The key to remove
     *
     * @return mixed
     */
    public function remove($key)
    {
        // TODO: Implement remove() method.
    }

    /**
     * Checks if the given key exists in the storage interface.
     *
     * @param mixed $key The key to remove
     *
     * @return mixed
     */
    public function exists($key)
    {
        // TODO: Implement exists() method.
    }
}

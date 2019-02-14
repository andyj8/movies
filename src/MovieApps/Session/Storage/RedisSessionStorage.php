<?php

namespace MovieApps\Session\Storage;

use EbysSdk\Storage\StorageInterface;
use MovieApps\Session\Session;
use Redis;

class RedisSessionStorage implements StorageInterface
{
    /**
     * @var Redis
     */
    private $redis;

    /**
     * RedisSession constructor.
     */
    public function __construct()
    {
        $this->redis = new Redis();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function set($key, $data)
    {
        $this->redis->set($key, $data);
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

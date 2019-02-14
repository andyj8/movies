<?php

namespace MovieApps\Session\Storage;

use EbysSdk\Storage\StorageInterface;

class FileSessionStorage implements StorageInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $path = '/tmp/' . $key . '.session';
        if (!file_exists($path)) {
            return null;
        }

        return unserialize(file_get_contents($path));
    }

    /**
     * @param $key
     * @param $data
     * @return mixed
     */
    public function set($key, $data)
    {
        $path = '/tmp/' . $key . '.session';
        file_put_contents($path, serialize($data));
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

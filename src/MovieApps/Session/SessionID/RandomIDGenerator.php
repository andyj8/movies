<?php

namespace MovieApps\Session\SessionID;

class RandomIDGenerator implements IDGenerator
{
    /**
     * @return string
     */
    public function generate()
    {
        return sha1(bin2hex(openssl_random_pseudo_bytes(128)));
    }
}

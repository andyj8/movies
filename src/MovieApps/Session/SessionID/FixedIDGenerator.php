<?php

namespace MovieApps\Session\SessionID;

class FixedIDGenerator implements IDGenerator
{
    /**
     * @return string
     */
    public function generate()
    {
        return 'TEST';
    }
}

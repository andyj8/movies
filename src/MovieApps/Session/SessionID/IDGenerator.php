<?php

namespace MovieApps\Session\SessionID;

interface IDGenerator
{
    /**
     * @return string
     */
    public function generate();
}

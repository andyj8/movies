<?php

namespace MovieApps\Session;

class Session
{
    /**
     * @var string
     */
    public $rmhAuthToken;

    /**
     * @var string
     */
    public $slapiAuthToken;

    /**
     * @param string $rmhAuthToken
     * @param string $slapiAuthToken
     */
    public function __construct($rmhAuthToken, $slapiAuthToken)
    {
        $this->rmhAuthToken = $rmhAuthToken;
        $this->slapiAuthToken = $slapiAuthToken;
    }
}

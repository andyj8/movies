<?php

namespace MovieApps\Service;

class ServiceResponse
{
    /**
     * @var string
     */
    public $provider;

    /**
     * @var mixed
     */
    public $response;

    /**
     * @param string $provider
     * @param mixed $response
     */
    public function __construct($provider, $response)
    {
        $this->provider = $provider;
        $this->response = $response;
    }
}

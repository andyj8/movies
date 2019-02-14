<?php

namespace MovieApps\Controller;

use InvalidArgumentException;
use MovieApps\Session\Session;

class Request
{
    const PROTOCOL_SOAP = 'soap';
    const PROTOCOL_JSON = 'json';

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $method;
    
    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $settings;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param $protocol
     * @param $endpoint
     * @param $method
     * @param array $params
     * @param array $settings
     * @param Session $session
     */
    public function __construct($protocol, $endpoint, $method, array $params = [], array $settings = [], Session $session = null)
    {
        if (!in_array($protocol, [self::PROTOCOL_SOAP, self::PROTOCOL_JSON])) {
            throw new InvalidArgumentException('Invalid protocol');
        }

        $settings['AuthToken'] = '';
        if ($session) {
            $settings['AuthToken'] = $session->rmhAuthToken;
        }

        $this->protocol = $protocol;
        $this->endpoint = $endpoint;
        $this->method = $method;
        $this->params = $params;
        $this->settings = $settings;
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function protocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function endpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function params()
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function settings()
    {
        return $this->settings;
    }

    /**
     * @param $setting
     * @return bool
     */
    public function hasSetting($setting)
    {
        return isset($this->settings[$setting]);
    }

    /**
     * @param $setting
     * @return array
     */
    public function getSetting($setting)
    {
        return $this->settings[$setting];
    }

    /**
     * @return Session
     */
    public function session()
    {
        return $this->session;
    }
}

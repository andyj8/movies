<?php

namespace MovieApps\Service\User;

use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use MovieApps\Client\SlapiClient;
use Psr\Log\LoggerInterface as Logger;

class ForgotPassword implements Service
{
    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param SlapiClient $slapiClient
     * @param Logger $logger
     */
    public function __construct(SlapiClient $slapiClient, Logger $logger)
    {
        $this->slapiClient = $slapiClient;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $data = ['email' => $params['Email']];
        $this->slapiClient->post('person/password-reset', $data);

        return Payload::success();
    }
}

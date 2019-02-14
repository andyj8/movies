<?php

namespace MovieApps\Service\User;

use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use MovieApps\Client\SlapiClient;
use MovieApps\Service\User\Response\GetUserBasicAccountInfoPayload;
use Psr\Log\LoggerInterface as Logger;

class GetUserBasicAccountInfo implements Service
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
        $result = $this->slapiClient->get('me');
        $payload = (array) $result->body->payload;
        
        if (empty($payload['id'])) {
            return Payload::userNotFound();
        }

        $response = new GetUserBasicAccountInfoPayload('0', 'Success');
        $response->firstName = $payload['first_name'];
        $response->lastName = $payload['last_name'];
        $response->email = $payload['email'];
        $response->username = $payload['email'];
        $response->dOB = '?';
        $response->termsAcceptance = '?';
        $response->newsletterAcceptance = '?';

        return $response;
    }
}

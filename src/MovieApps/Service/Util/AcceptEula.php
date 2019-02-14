<?php

namespace MovieApps\Service\Util;

use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class AcceptEula implements Service
{
    const EULA_ACCEPTED = 'movies_eula_accepted';

    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @param SlapiClient $slapiClient
     */
    public function __construct(SlapiClient $slapiClient)
    {
        $this->slapiClient = $slapiClient;
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

        $data = [
            'key'   => self::EULA_ACCEPTED,
            'value' => '1'
        ];

        $this->slapiClient->post('person/' . $payload['id'] . '/data', $data);
        
        return Payload::success();
    }
}

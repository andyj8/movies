<?php

namespace MovieApps\Service\User;

use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class GetParentalControl implements Service
{
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

        $response = Payload::success();
        $response->warnings = '';
        $response->customData = '';
        $response->filterContent = 'false';
        $response->requirePin = 'false';
        $response->ratingsList = '50,51,52,53,54,55,56,57';
        $response->PIN = '';

        $result = $this->slapiClient->get('person/' . $payload['id'] . '/parentalSetting');
        $payload = (array) $result->body->payload;

        if (empty($payload['id'])) {
            return $response;
        }

        $response->filterContent = !empty($payload['filter_content']) ? 'true' : 'false';
        $response->requirePin = !empty($payload['require_pin_on_purchase']) ? 'true' : 'false';
        $response->PIN = $payload['pin'];

        $mappings = [
            'U'   => '50',
            'PG'  => '51',
            '12A' => '52',
            '12'  => '53',
            '15'  => '54',
            '18'  => '55',
            'R18' => '56'
        ];

        $key = array_search($payload['max_movie_rating'], array_keys($mappings));
        $ratings = array_slice(array_values($mappings), $key);

        if (!empty($payload['allow_nr_ratings'])) {
            $ratings[] = '57';
        }

        $response->ratingsList = implode(',', $ratings);

        return $response;
    }
}

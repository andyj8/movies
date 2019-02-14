<?php

namespace MovieApps\Service\Commerce;

use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class GetBillingInfo implements Service
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
     * @return array
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('me/payment-cards');

        $response = Payload::success();
        $response->giftCertificateBalance = '0';
        $response->cCLast4 = '';

        foreach ($result->body->payload as $card) {
            if (!empty($card->preferred)) {
                $response->cCLast4 = $card->card_number;
            }
        }

        return $response;
    }
}

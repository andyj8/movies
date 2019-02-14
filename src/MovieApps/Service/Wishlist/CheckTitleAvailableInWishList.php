<?php

namespace MovieApps\Service\Wishlist;

use Psr\Log\LoggerInterface as Logger;
use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class CheckTitleAvailableInWishList implements Service
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
     * @return Payload
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('video/title/' . $params['TitleID']);

        if ($result->code == 204) {
            return Payload::titleNotFound();
        }

        $result = $this->slapiClient->get('me/wishlist');

        if ($result->code == 401) {
            return Payload::noAuth();
        }

        foreach ($result->body->payload as $item) {
            if ($item->product_sku == $result->body->payload->sku) {
                return Payload::success();
            }
        }

        $response = new Payload();
        $response->responseCode = '20';
        $response->responseMessage = 'Title ID not found.';

        return $response;
    }
}

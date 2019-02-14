<?php

namespace MovieApps\Service\Wishlist;

use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use Psr\Log\LoggerInterface as Logger;

class AddItemToWishList implements Service
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
        $result = $this->slapiClient->get('video/title/' . $params['TitleID']);

        if ($result->code != 200) {
            return Payload::titleNotFound();
        }

        $sku = $result->body->payload->sku;
        $result = $this->slapiClient->post('me/wishlist', ['product_sku' => $sku]);
        
        if ($result->code == 401) {
            return Payload::noAuth();
        }

        if ($result->code == 409) {
            $response = new Payload();
            $response->responseCode = '47';
            $response->responseMessage = 'Title ID Has Existed In Wishlist';
            return $response;
        }

        if ($result->code == 201) {
            return Payload::success();
        }
        
        return Payload::unhandledException();
    }
}

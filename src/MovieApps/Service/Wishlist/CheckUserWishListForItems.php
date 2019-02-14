<?php

namespace MovieApps\Service\Wishlist;

use MovieApps\Response\Payload;
use Psr\Log\LoggerInterface as Logger;
use MovieApps\Client\SlapiClient;
use MovieApps\Service\Service;

class CheckUserWishListForItems implements Service
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
        $result = $this->slapiClient->get('me/wishlist');

        if ($result->code == 401) {
            return Payload::noAuth();
        }

        return !empty($result->body->payload);
    }
}

<?php

namespace MovieApps\Service\Commerce;

use Exception;
use MovieApps\Response\Payload;
use MovieApps\Service\Commerce\Shared\SlapiBasketCreator;
use MovieApps\Service\Service;
use MovieApps\Client\SlapiClient;
use Psr\Log\LoggerInterface as Logger;

class CalcOrderTax implements Service
{
    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @var SlapiBasketCreator
     */
    private $basketCreator;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param SlapiClient $slapiClient
     * @param $basketCreator
     * @param Logger $logger
     */
    public function __construct(SlapiClient $slapiClient, $basketCreator, Logger $logger)
    {
        $this->slapiClient = $slapiClient;
        $this->basketCreator = $basketCreator;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('video/product/' . $params['SKUID']);
        if ($result->code == 204) {
            return Payload::titleNotFound();
        }

        $sku = $result->body->payload->sku;

        try {
            $basket = $this->basketCreator->createBasket($sku, $params['Code']);
        } catch (Exception $e) {
            return Payload::unhandledException($e->getMessage());
        }

        $response = Payload::success();
        $response->subTotal = $basket->subtotal_inc_vat;
        $response->totalPrice = $basket->total_inc_vat;
        $response->tax = '0';
        $response->taxRate = $basket->vat_rate;

        return $response;
    }
}

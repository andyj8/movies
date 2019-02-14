<?php

namespace MovieApps\Service\Commerce;

use Exception;
use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Commerce\Shared\SlapiBasketCreator;
use MovieApps\Service\Service;

class DoPurchase implements Service
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
     * @param SlapiClient $slapiClient
     * @param SlapiBasketCreator $basketCreator
     */
    public function __construct(SlapiClient $slapiClient, SlapiBasketCreator $basketCreator)
    {
        $this->slapiClient = $slapiClient;
        $this->basketCreator = $basketCreator;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('me/payment-cards');
        if (empty($result->body->payload)) {
            $response = new Payload('11', 'User Credit Card Not On File');
            $response->passID = '0';
            $response->transactionAuthNum = '0-';
            return $response;
        }

        $result = $this->slapiClient->get('video/product/' . $params['SKUID']);
        if ($result->code == 204) {
            return Payload::titleNotFound();
        }

        $sku = $result->body->payload->sku;

        try {
            $basket = $this->basketCreator->createBasket($sku, $params['CouponCode']);
        } catch (Exception $e) {
            return Payload::unhandledException($e->getMessage());
        }

        // is order free ? adyen/denim

        $result = $this->slapiClient->post('basket/' . $basket->id . '/finalise', [
            'providerName' => '',
            'providerDetails' => [
                'clientIp' => '',
                'cardRef'  => '',
                'cvv'      => ''
            ]
        ]);

        // doPurchaseNoBilling

        $response = Payload::success();
        $response->passID = '';
        $response->transactionAuthNum = '';
        
        return $response;
    }
}

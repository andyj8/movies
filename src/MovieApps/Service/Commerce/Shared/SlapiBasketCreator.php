<?php

namespace MovieApps\Service\Commerce\Shared;

use Exception;
use MovieApps\Client\SlapiClient;

class SlapiBasketCreator
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
     * @param string $sku
     * @param string $couponCode
     * @return \stdClass
     * @throws Exception
     */
    public function createBasket($sku, $couponCode)
    {
        $result = $this->slapiClient->post('basket');
        if ($result->code != 201) {
            throw new Exception('Failed to create basket');
        }

        $basketId = $result->body->payload->id;

        if ($couponCode) {
            $data = ['code' => $couponCode];
            $this->slapiClient->post('basket/' . $basketId . '/voucher', $data);
        }

        $data = ['sku' => $sku];
        $result = $this->slapiClient->post('basket/' . $basketId . '/item', $data);
        if ($result->code != 201) {
            throw new Exception('Failed to add basket item');
        }

        return $result->body->payload;
    }
}

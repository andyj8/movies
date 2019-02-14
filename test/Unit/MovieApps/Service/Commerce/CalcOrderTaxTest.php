<?php

namespace MovieApps\Service\Commerce;

use MovieApps\Service\Commerce\Shared\SlapiBasketCreator;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class CalcOrderTaxTest extends PHPUnit_Framework_TestCase
{
    public function testCalculatesOrderTax()
    {
        $params = ['SKUID' => '1234', 'Code' => ''];

        $logger = m::mock('Psr\Log\LoggerInterface');
        $logger->shouldIgnoreMissing();

        $client = m::mock('MovieApps\Client\SlapiClient');
        $client->shouldReceive('get')->once()->andReturn($this->getProductApiResult());
        $client->shouldReceive('post')->once()->andReturn($this->getCreateBasketApiResult());
        $client->shouldReceive('post')->once()->andReturn($this->getBasketItemApiResult());

        $basketCreator = new SlapiBasketCreator($client);
        $service = new CalcOrderTax($client, $basketCreator, $logger);
        $response = $service->usingEnts($params, []);

        $this->assertEquals('9.99', $response->subTotal);
        $this->assertEquals('19.99', $response->totalPrice);
    }

    public function testCalculatesOrderTaxWithCouponCode()
    {
        $params = ['SKUID' => '1234', 'Code' => 'ABC'];

        $logger = m::mock('Psr\Log\LoggerInterface');
        $logger->shouldIgnoreMissing();

        $client = m::mock('MovieApps\Client\SlapiClient');
        $client->shouldReceive('get')->once()->andReturn($this->getProductApiResult());
        $client->shouldReceive('post')->once()->andReturn($this->getCreateBasketApiResult());
        $client->shouldReceive('post')->once();
        $client->shouldReceive('post')->once()->andReturn($this->getBasketItemApiResult());

        $basketCreator = new SlapiBasketCreator($client);
        $service = new CalcOrderTax($client, $basketCreator, $logger);
        $service->usingEnts($params, []);
    }

    /**
     * @return \stdClass
     */
    private function getProductApiResult()
    {
        $result = new \stdClass();
        $result->code = 200;
        $body = new \stdClass();
        $payload = new \stdClass();
        
        $payload->sku = 'VID-123';

        $body->payload = $payload;
        $result->body = $body;

        return $result;
    }

    /**
     * @return \stdClass
     */
    private function getCreateBasketApiResult()
    {
        $result = new \stdClass();
        $result->code = 201;
        $body = new \stdClass();
        $payload = new \stdClass();
        
        $payload->id = 100;

        $body->payload = $payload;
        $result->body = $body;

        return $result;
    }

    /**
     * @return \stdClass
     */
    private function getBasketItemApiResult()
    {
        $result = new \stdClass();
        $result->code = 201;
        $body = new \stdClass();
        $payload = new \stdClass();

        $payload->subtotal_inc_vat = '9.99';
        $payload->total_inc_vat = '19.99';
        $payload->vat_rate = '20';

        $body->payload = $payload;
        $result->body = $body;

        return $result;
    }
}

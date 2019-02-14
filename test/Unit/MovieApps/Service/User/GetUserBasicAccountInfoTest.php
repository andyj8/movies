<?php

namespace MovieApps\Service\User;

use PHPUnit_Framework_TestCase;
use Mockery as m;

class GetUserBasicAccountInfoTest extends PHPUnit_Framework_TestCase
{
    public function testGetsAccount()
    {
        $settings = ['AuthToken' => 'abc'];

        $result = new \stdClass();
        $body = new \stdClass();
        $body->payload = [
            'id'         => 'myid',
            'first_name' => 'joe',
            'last_name'  => 'bloggs',
            'email'      => 'joe@email.com'
        ];
        $result->body = $body;
        
        $logger = m::mock('Psr\Log\LoggerInterface');
        $logger->shouldIgnoreMissing();

        $client = m::mock('MovieApps\Client\SlapiClient');
        $client->shouldReceive('get')->andReturn($result);
        $client->shouldReceive('setAuthToken')->with('abc');
        
        $service = new GetUserBasicAccountInfo($client, $logger);
        $response = $service->usingEnts([], $settings);

        $this->assertEquals('joe', $response->firstName);
        $this->assertEquals('bloggs', $response->lastName);
        $this->assertEquals('joe@email.com', $response->email);
    }

    public function testReturnsNotFoundError()
    {
        $settings = ['AuthToken' => 'abc'];

        $result = new \stdClass();
        $body = new \stdClass();
        $body->payload = [];
        $result->body = $body;

        $logger = m::mock('Psr\Log\LoggerInterface');
        $logger->shouldIgnoreMissing();

        $client = m::mock('MovieApps\Client\SlapiClient');
        $client->shouldReceive('get')->andReturn($result);

        $service = new GetUserBasicAccountInfo($client, $logger);
        $response = $service->usingEnts([], $settings);

        $this->assertEquals('601', $response->responseCode);
    }
}

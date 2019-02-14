<?php

namespace MovieApps\Service\User;

use EbysSdk\Storage\StorageInterface;
use MovieApps\Session\SessionID\FixedIDGenerator;
use MovieApps\Session\Storage\InMemorySessionStorage;
use PHPUnit_Framework_TestCase;
use Mockery as m;

class LoginUserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $params = [
        'Username' => 'user',
        'Password' => 'pass'
    ];

    /**
     * @var StorageInterface
     */
    private $storage;

    public function setUp()
    {
        $this->storage = new InMemorySessionStorage();
    }

    public function testSuccessfulLogin()
    {
        $service = $this->getService(true, true);
        $response = $service->usingEnts($this->params, []);

        $this->assertEquals('TEST', $response->authToken);

        $session = $this->storage->get('TEST');
        $this->assertEquals('abc', $session->slapiAuthToken);
        $this->assertEquals('xyz', $session->rmhAuthToken);
    }

    public function testLoginFailureDueToSlapi()
    {
        $service = $this->getService(false, true);
        $response = $service->usingEnts($this->params, []);

        $this->assertEquals('52', $response->responseCode);
        $this->assertNull($this->storage->get('TEST'));
    }

    public function testLoginFailureDueToRMH()
    {
        $service = $this->getService(true, false);
        $response = $service->usingEnts($this->params, []);

        $this->assertEquals('52', $response->responseCode);
        $this->assertNull($this->storage->get('TEST'));
    }

    private function getService($slapiTokenOk, $rmhTokenOk)
    {
        $result = new \stdClass();
        $body = new \stdClass();
        $result->body = $body;
        $body->payload = new \stdClass();

        if ($slapiTokenOk) {
            $result->body->payload->access_token = 'abc';
        }

        $slapi = m::mock('MovieApps\Client\SlapiClient');
        $slapi->shouldReceive('post')->once()->andReturn($result);

        $code = ($rmhTokenOk) ? '0' : '52';
        $result = ['responseCode' => $code, 'authToken' => 'xyz'];
        $rmh = m::mock('RmhApiClient\Service\ServiceEndpoints\Authentication');
        $rmh->shouldReceive('loginUser')->once()->andReturn($result);

        $idGen = new FixedIDGenerator();

        $logger = m::mock('Psr\Log\LoggerInterface');
        $logger->shouldIgnoreMissing();

        return new LoginUser($slapi, $rmh, $this->storage, $idGen, $logger);
    }
}

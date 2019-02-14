<?php

namespace MovieApps\Service\User;

use PHPUnit_Framework_TestCase;
use Mockery as m;

class RegisterUserTest extends PHPUnit_Framework_TestCase
{
    private $logger;

    /**
     * @var array
     */
    private $params = [
        'FirstName'  => 'joe',
        'LastName'   => 'bloggs',
        'Email'      => 'joe@email.com',
        'Password'   => 'secret',
        'ClientName' => 'ccc',
        'AppVersion' => '1'
    ];

    public function setUp()
    {
        $this->logger = m::mock('Psr\Log\LoggerInterface');
        $this->logger->shouldIgnoreMissing();
    }

    public function testSuccessfulRegistersUser()
    {
        $slapi = m::mock('MovieApps\Client\SlapiClient');
        $slapi->shouldReceive('post')->once()->andReturn($this->getApiResult(201));

        $result = ['responseCode' => '0'];
        $rmh = m::mock('RmhApiClient\Service\ServiceEndpoints\Authentication');
        $rmh->shouldReceive('registerUser')->once()->andReturn($result);

        $loginUser = m::mock('MovieApps\Service\User\LoginUser');
        $loginUser->shouldReceive('usingEnts')->once();

        $service = new RegisterUser($slapi, $rmh, $loginUser, $this->logger);
        $service->usingEnts($this->params, []);
    }

    public function testRegisterFailureDueToSlapi()
    {
        $slapi = m::mock('MovieApps\Client\SlapiClient');
        $slapi->shouldReceive('post')->once()->andReturn($this->getApiResult(409));

        $result = ['responseCode' => '0'];
        $rmh = m::mock('RmhApiClient\Service\ServiceEndpoints\Authentication');
        $rmh->shouldReceive('registerUser')->once()->andReturn($result);

        $loginUser = m::mock('MovieApps\Service\User\LoginUser');

        $service = new RegisterUser($slapi, $rmh, $loginUser, $this->logger);
        $result = $service->usingEnts($this->params, []);

        $this->assertEquals('50', $result->responseCode);
    }

    public function testRegisterFailureDueToRMH()
    {
        $slapi = m::mock('MovieApps\Client\SlapiClient');
        $slapi->shouldReceive('post')->once()->andReturn($this->getApiResult(201));

        $result = ['responseCode' => '50'];
        $rmh = m::mock('RmhApiClient\Service\ServiceEndpoints\Authentication');
        $rmh->shouldReceive('registerUser')->once()->andReturn($result);

        $loginUser = m::mock('MovieApps\Service\User\LoginUser');

        $service = new RegisterUser($slapi, $rmh, $loginUser, $this->logger);
        $result = $service->usingEnts($this->params, []);

        $this->assertEquals('50', $result->responseCode);
    }

    /**
     * @param $code
     * @return \stdClass
     */
    private function getApiResult($code)
    {
        $result = new \stdClass();
        $body = new \stdClass();
        $body->payload = [];
        $result->body = $body;
        $result->code = $code;

        return $result;
    }
}

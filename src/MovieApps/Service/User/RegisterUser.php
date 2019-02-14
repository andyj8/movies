<?php

namespace MovieApps\Service\User;

use Exception;
use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use MovieApps\Service\User\Exception\UsernameAlreadyExists;
use MovieApps\Service\User\Response\LoginUserPayload;
use Psr\Log\LoggerInterface as Logger;
use RmhApiClient\Service\ServiceEndpoints\Authentication as RMHService;

class RegisterUser implements Service
{
    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @var RMHService
     */
    private $rmhService;

    /**
     * @var LoginUser
     */
    private $loginUser;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param SlapiClient $slapiClient
     * @param RMHService $rmhEndpoint
     * @param LoginUser $loginUser
     * @param Logger $logger
     */
    public function __construct(
        SlapiClient $slapiClient,
        RMHService $rmhEndpoint,
        LoginUser $loginUser,
        Logger $logger
    ) {
        $this->slapiClient = $slapiClient;
        $this->rmhService = $rmhEndpoint;
        $this->loginUser = $loginUser;
        $this->logger = $logger;
    }
    
    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        try {
            $this->registerOnRMH($params, $settings);
            $this->registerOnSlapi($params);
            
        } catch (UsernameAlreadyExists $e) {
            return new LoginUserPayload('50', 'Username Already Exists');
            
        } catch (Exception $e) {
            return Payload::unhandledException($e->getMessage());
        }

        $loginParams = [
            'Username'   => $params['Email'],
            'Password'   => $params['Password'],
            'ClientName' => $params['ClientName'],
            'AppVersion' => $params['AppVersion']
        ];

        return $this->loginUser->usingEnts($loginParams, $settings);
    }

    /**
     * @param array $params
     * @param array $settings
     * @throws Exception
     * @throws UsernameAlreadyExists
     */
    private function registerOnRMH(array $params, array $settings)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->rmhService->registerUser($params, $settings);

        if ($result['responseCode'] == '50') {
            throw new UsernameAlreadyExists();
        }

        if ($result['responseCode'] != '0') {
            throw new Exception();
        }
    }

    /**
     * @param array $params
     * @throws Exception
     * @throws UsernameAlreadyExists
     */
    private function registerOnSlapi(array $params)
    {
        $data = [
            'first_name'          => $params['FirstName'],
            'last_name'           => $params['LastName'],
            'display_name'        => $params['FirstName'] . ' ' . $params['LastName'],
            'email'               => $params['Email'],
            'password'            => $params['Password'],
            'registration_source' => 'App'
        ];

        $result = $this->slapiClient->post('person', $data);

        if ($result->code == 409) {
            throw new UsernameAlreadyExists();
        }

        if ($result->code !== 201) {
            throw new Exception();
        }
    }
}

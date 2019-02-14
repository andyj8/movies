<?php

namespace MovieApps\Service\User;

use EbysSdk\Storage\StorageInterface;
use Exception;
use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use MovieApps\Service\User\Exception\LoginFailure;
use MovieApps\Service\User\Response\LoginUserPayload;
use MovieApps\Session\SessionID\IDGenerator;
use MovieApps\Session\Session;
use RmhApiClient\Service\ServiceEndpoints\Authentication as RMHService;
use Psr\Log\LoggerInterface as Logger;

class LoginUser implements Service
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
     * @var StorageInterface
     */
    private $session;

    /**
     * @var IDGenerator
     */
    private $sessionIDGenerator;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param SlapiClient $slapiClient
     * @param RMHService $rmhService
     * @param StorageInterface $session
     * @param IDGenerator $sessionIDGenerator
     * @param Logger $logger
     */
    public function __construct(
        SlapiClient $slapiClient,
        RMHService $rmhService,
        StorageInterface $session,
        IDGenerator $sessionIDGenerator,
        Logger $logger
    ) {
        $this->slapiClient = $slapiClient;
        $this->rmhService = $rmhService;
        $this->session = $session;
        $this->sessionIDGenerator = $sessionIDGenerator;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $response = new LoginUserPayload();

        try {
            $rmhToken = $this->loginToRMH($params, $settings);
            $slapiToken = $this->loginToSlapi($params);

            $sessionID = $this->sessionIDGenerator->generate();
            $this->session->set($sessionID, new Session($rmhToken, $slapiToken));

        } catch (LoginFailure $e) {
            $response->responseCode = '52';
            $response->responseMessage = $e->getMessage();
            return $response;

        } catch (Exception $e) {
            return Payload::unhandledException($e->getMessage());
        }

        $response->responseCode = '0';
        $response->responseMessage = 'Success';
        $response->authToken = $sessionID;
        $response->emailAddress = '';
        $response->deviceFriendlyName = '';

        return $response;
    }

    /**
     * @param array $params
     * @return string
     * @throws LoginFailure
     */
    private function loginToSlapi(array $params)
    {
        $data = [
            'username'      => $params['Username'],
            'password'      => $params['Password'],
            'client_id'     => 'postman',
            'client_secret' => 'password',
            'grant_type'    => 'password'
        ];

        $result = $this->slapiClient->post('token', $data);
        $payload = $result->body->payload;

        if (!isset($payload->access_token)) {
            throw new LoginFailure('Slapi Password Mismatch');
        }

        return $payload->access_token;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     * @throws Exception
     * @throws LoginFailure
     */
    private function loginToRMH(array $params, array $settings)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $result = $this->rmhService->loginUser($params, $settings);

        if ($result['responseCode'] == '2') {
            throw new LoginFailure('RMH Invalid User ID');
        }

        if ($result['responseCode'] == '52') {
            throw new LoginFailure('RMH Password Mismatch');
        }

        if (!$result['authToken']) {
            throw new Exception('RMH No Auth Token');
        }

        return $result['authToken'];
    }
}

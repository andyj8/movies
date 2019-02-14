<?php

namespace MovieApps\Service\Auth;

use MovieApps\Client\SlapiClient;
use MovieApps\Service\Service;
use RmhApiClient\Service\ServiceEndpoints\Authentication as RMHService;
use Psr\Log\LoggerInterface as Logger;

class VerifyAuthToken implements Service
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
     * @var Logger
     */
    private $logger;

    /**
     * @param SlapiClient $slapiClient
     * @param RMHService $rmhService
     * @param Logger $logger
     */
    public function __construct(SlapiClient $slapiClient, RMHService $rmhService, Logger $logger)
    {
        $this->slapiClient = $slapiClient;
        $this->rmhService = $rmhService;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        if ($this->slapiClient->get('me')->code != 200) {
            return 'false';
        }

        /** @noinspection PhpUndefinedMethodInspection */
        if (!$this->rmhService->verifyAuthToken($params, $settings)) {
            return 'false';
        }

        return 'true';
    }
}

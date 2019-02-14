<?php

namespace MovieApps\Service\Library;

use MovieApps\Client\SlapiClient;
use MovieApps\Response\Payload;
use MovieApps\Service\Library\Response\LibraryItemPayloadFactory;
use MovieApps\Service\Service;
use MovieApps\Service\TitleData\GetFullSummary;

class GetPurchasedTitle implements Service
{
    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @var GetFullSummary
     */
    private $getFullSummary;

    /**
     * @var LibraryItemPayloadFactory
     */
    private $responseFactory;

    /**
     * @param SlapiClient $slapiClient
     * @param GetFullSummary $getFullSummary
     * @param LibraryItemPayloadFactory $responseFactory
     */
    public function __construct(
        SlapiClient $slapiClient,
        GetFullSummary $getFullSummary,
        LibraryItemPayloadFactory $responseFactory
    ) {
        $this->slapiClient = $slapiClient;
        $this->getFullSummary = $getFullSummary;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('me/library/video/' . $params['PassID']);
        if ($result->code == 204) {
            return new Payload('21', 'Could not find pass id');
        }

        $libraryItem = $result->body->payload;

        $params = ['TitleID' => $libraryItem->titleID];
        $product = $this->getFullSummary->usingEnts($params, $settings);

        $response = $this->responseFactory->createPayload($product, (array) $libraryItem);

        return $response;
    }
}

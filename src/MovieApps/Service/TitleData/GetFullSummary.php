<?php

namespace MovieApps\Service\TitleData;

use EbysSdk\Service\Product;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use MovieApps\Service\TitleData\Response\FullSummaryPayloadFactory;

class GetFullSummary implements Service
{
    /**
     * @var Product
     */
    private $sdkProductService;

    /**
     * @var FullSummaryPayloadFactory
     */
    private $responseFactory;

    /**
     * @param Product $sdkProductService
     * @param FullSummaryPayloadFactory $responseFactory
     */
    public function __construct(Product $sdkProductService, FullSummaryPayloadFactory $responseFactory)
    {
        $this->sdkProductService = $sdkProductService;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->sdkProductService->retrieveVideoByRmhId($params['TitleID']);

        if (empty($result)) {
            return new Payload('20', 'Title ID Not Found');
        }

        return $this->responseFactory->createPayload($result->getPayload(), $params, $settings);
    }
}

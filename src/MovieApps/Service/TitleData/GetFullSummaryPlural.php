<?php

namespace MovieApps\Service\TitleData;

use EbysSdk\Service\Product;
use MovieApps\Response\Collection;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use MovieApps\Service\TitleData\Response\FullSummaryPayloadFactory;

class GetFullSummaryPlural implements Service
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
     * @return Collection
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $ids = [];
        foreach (explode(',', $params['TitleIDs']) as $titleId) {
            $ids[] = $titleId;
        }

        $result = $this->sdkProductService->retrieveVideosByRmhId($ids);

        if (empty($result)) {
            return new Payload('20', 'Title ID Not Found');
        }
        
        $summaries = [];
        foreach ($result->toArray() as $hit) {
            $summaries[] = $this->responseFactory->createPayload($hit, $params, $settings);
        }
        
        return new Collection('FullTitle', $summaries);
    }
}

<?php

namespace MovieApps\Service\Search;

use EbysSdk\Service\Search;
use MovieApps\Response\Generic\ListedProductsFactory;
use MovieApps\Service\Service;

class SearchTitleSetOptions implements Service
{
    /**
     * @var Search
     */
    private $sdkSearchService;

    /**
     * @var ListedProductsFactory
     */
    private $responseFactory;

    /**
     * @param Search $sdkSearchService
     * @param ListedProductsFactory $responseFactory
     */
    public function __construct(Search $sdkSearchService, ListedProductsFactory $responseFactory)
    {
        $this->sdkSearchService = $sdkSearchService;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $from = ceil($params['ItemsPerPage'] * ($params['PageNum'] - 1));

        $fields = [
            'sub-type',
            'title',
            'rmh_id',
            'image_name',
        ];

        $result = $this->sdkSearchService->searchProducts(
            'video', $params['Query'], $from, $params['ItemsPerPage'], $fields
        );

        return $this->responseFactory->createPayload($result->toArray(), $params);
    }
}

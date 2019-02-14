<?php

namespace MovieApps\Service\Browse;

use EbysSdk\Service\Product;
use MovieApps\Response\Generic\ListedProductsFactory;
use MovieApps\Service\Service;

class GetBrowseList implements Service
{
    /**
     * @var Product
     */
    private $sdkProductService;

    /**
     * @var ListedProductsFactory
     */
    private $responseFactory;

    /**
     * @param Product $sdkProductService
     * @param ListedProductsFactory $responseFactory
     */
    public function __construct(Product $sdkProductService, ListedProductsFactory $responseFactory)
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
        $from = ceil($params['ItemsPerPage'] * ($params['PageNum'] - 1));

        $fields = [
            'sub-type',
            'title',
            'rmh_id',
            'image_name',
        ];

        $result = $this->sdkProductService->retrieveByGenreId(
            $params['GenreID'], $fields, $params['ItemsPerPage'], $from
        );

        return $this->responseFactory->createPayload($result->toArray(), $params);
    }
}

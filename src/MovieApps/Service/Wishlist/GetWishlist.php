<?php

namespace MovieApps\Service\Wishlist;

use EbysSdk\Service\Product;
use MovieApps\Client\SlapiClient;
use MovieApps\Response\Collection;
use MovieApps\Response\Generic\ListedProduct;
use MovieApps\Response\Generic\ListProductsPayload;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;
use Psr\Log\LoggerInterface as Logger;

class GetWishlist implements Service
{
    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @var Product
     */
    private $sdkProductService;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param SlapiClient $slapiClient
     * @param Product $sdkProductService
     * @param Logger $logger
     */
    public function __construct(SlapiClient $slapiClient, Product $sdkProductService, Logger $logger)
    {
        $this->slapiClient = $slapiClient;
        $this->sdkProductService = $sdkProductService;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('me/wishlist');

        if ($result->code == 401) {
            return Payload::noAuth();
        }

        $skus = [];
        foreach ($result->body->payload as $item) {
            $skus[] = $item->product_sku;
        }

        $items = $this->createItems($skus);

        $response = new ListProductsPayload('0', 'Success');
        $response->sort = 'standard';
        $response->purchaseType = 'any';
        $response->profile = 'none';
        $response->pageNum = '1';
        $response->itemsPerPage = (string) count($items);
        $response->totalPages = '1';
        $response->totalItems = (string) count($items);
        $response->genreID = '0';
        $response->items = new Collection('browseItem', $items);

        return $response;
    }

    /**
     * @param array $skus
     * @return array
     */
    private function createItems(array $skus)
    {
        if (empty($skus)) {
            return [];
        }

        $items = [];

        $result = $this->sdkProductService->retrieveBySkus($skus);

        foreach ($result->toArray() as $product) {
            $product = (array) $product;
            $item = new ListedProduct();
            $item->titleID = $product['rmh_id'];
            $item->boxartPrefix = $product['image_name'];
            $item->name = $product['title'];
            $item->isBundle = in_array($product['sub-type'], ['bundle', 'show', 'season']);
            $item->isInBundle = in_array($product['sub-type'], ['season', 'episode']);

            $items[] = $item;
        }

        return $items;
    }
}

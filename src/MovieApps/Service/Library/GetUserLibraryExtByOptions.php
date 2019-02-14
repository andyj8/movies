<?php

namespace MovieApps\Service\Library;

use EbysSdk\Client\ResponseSet;
use EbysSdk\Service\Product;
use MovieApps\Client\SlapiClient;
use MovieApps\Response\Collection;
use MovieApps\Response\Payload;
use MovieApps\Service\Library\Response\LibraryItemPayloadFactory;
use MovieApps\Service\Service;

class GetUserLibraryExtByOptions implements Service
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
     * @var LibraryItemPayloadFactory
     */
    private $responseFactory;

    /**
     * @param SlapiClient $slapiClient
     * @param Product $sdkProductService
     * @param LibraryItemPayloadFactory $responseFactory
     */
    public function __construct(
        SlapiClient $slapiClient,
        Product $sdkProductService,
        LibraryItemPayloadFactory $responseFactory
    ) {
        $this->slapiClient = $slapiClient;
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
        $result = $this->slapiClient->get('me/library/video');

        $ids = [];
        foreach ($result->body->payload as $item) {
            $ids[$item->titleID] = $item;
        }

        $result = $this->sdkProductService->retrieveVideosByRmhId(array_keys($ids), [], ['products'], 9999, 0);

        $items = [];
        foreach ($result->toArray() as $hit) {
            if (empty($hit['children'])) {
                $libraryItem = (array) $ids[$hit['rmh_id']];
                $sourceData = json_decode($hit['source_data'], true);
                $item = $this->responseFactory->createPayload($sourceData, $libraryItem);
                $items[] = $this->stripFields($item);
            }
        }

        $response = Payload::success();
        $response->sort = 'dateAdded'; // todo
        $response->purchaseType = 'any';
        $response->profile = 'none';
        $response->pageNum = '1';
        $response->itemsPerPage = '9999';
        $response->totalPages = '1';
        $response->totalItems = (string) count($items);
        $response->items = new Collection('LibraryBrowseItem', $items);
        $response->purchasedBundles = $this->createBundles($result, $ids);

        return $response;
    }

    /**
     * @param $result
     * @param array $ids
     * @return Collection
     */
    private function createBundles(ResponseSet $result, array $ids)
    {
        $items = [];

        foreach ($result->toArray() as $hit) {
            if (!empty($hit['children'])) {
                $libraryItem = (array) $ids[$hit['rmh_id']];
                $item = $this->createBundleItem('true', $hit, $libraryItem);

                $children = [];
                foreach ($hit['children'] as $child) {
                    $libraryItem = (array) $ids[$child['rmh_id']];
                    $children[] = $this->createBundleItem('false', $child, $libraryItem);
                }

                $item['bundleItems'] = new Collection('LibraryBundleItem', $children);
                $items[] = $item;
            }
        }

        return new Collection('LibraryBundleItem', $items);
    }

    /**
     * @param $isBundle
     * @param array $hit
     * @param array $libraryItem
     * @return array
     */
    private function createBundleItem($isBundle, array $hit, array $libraryItem)
    {
        return [
            'isBundle'     => $isBundle,
            'titleID'      => $hit['rmh_id'],
            'titleType'    => ($hit['sub-type'] == 'bundle') ? 'Movie' : 'TV_Season',
            'name'         => $hit['title'],
            'passID'       => $libraryItem['passID'],
            'skuID'        => $libraryItem['purchasedSkuID'],
            'boxartPrefix' => $hit['image_name'],
            'itemNumber'   => $hit['title_number'],
            'bundleItems'  => ''
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    private function stripFields(array $data)
    {
        unset($data['rentAvail']);
        unset($data['rentPrice']);
        unset($data['buyAvail']);
        unset($data['buyPrice']);
        unset($data['responseCode']);
        unset($data['responseMessage']);

        return $data;
    }
}

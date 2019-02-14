<?php

namespace MovieApps\Service\TitleData\Response;

use MovieApps\Response\Collection;
use MovieApps\Response\KeyValuePairCollection;
use MovieApps\Service\Wishlist\CheckTitleAvailableInWishList as WishlistService;

class FullSummaryPayloadFactory
{
    /**
     * @var WishlistService
     */
    private $wishlistService;

    /**
     * @param WishlistService $wishlistService
     */
    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    /**
     * @param array $data
     * @param array $params
     * @param array $settings
     * @return array
     */
    public function createPayload(array $data, array $params, array $settings)
    {
        $response = json_decode($data['source_data'], true);
        
        $inWishlist = false;
        if (isset($settings['AuthToken'])) {
            $wishlistResponse = $this->wishlistService->usingEnts($params, $settings);
            $inWishlist = $wishlistResponse->responseCode == '0';
        }
        
        $response['buyPrice'] = $data['buy_price_from'];
        $response['rentPrice'] = $data['rent_price_from'];

        $response['metaValues'] = $this->getMetaCollection($response, $inWishlist);
        $response['availableProducts'] = $this->getProductCollection($data);
        $response['wheelItems'] = '';

        // todo availableClosedCaptions

        if ($response['bonusAssets']) {
            $asset = $response['bonusAssets']['bonusAsset'];
            $response['bonusAssets'] = new Collection('bonusAsset', [$asset]);
        }
        
        return $response;
    }

    /**
     * @param array $response
     * @param bool $inWishlist
     * @return Collection
     */
    private function getMetaCollection(array $response, $inWishlist)
    {
        $metaCollection = new KeyValuePairCollection();

        foreach ($response['metaValues']['nameAndValue'] as $meta) {
            if ($meta['keyName'] == 'InUserWishlist') {
                $meta['keyValue'] = ($inWishlist) ? 'True' : 'False';
            }
            $metaCollection->addRow($meta['keyName'], $meta['keyValue']);
        }

        return $metaCollection;
    }

    /**
     * @param array $source
     * @return Collection
     */
    private function getProductCollection(array $source)
    {
        $productCollection = [];

        foreach ($source['products'] as $product) {
            $productSource = json_decode($product['source_data'], true);
            $productSource['price'] = $product['price']['sales_price_inc_vat'];
            $productSource['availableAssets'] = $this->getAssetCollection($productSource);
            $productCollection[] = $productSource;
        }

        return new Collection('product', $productCollection);
    }

    /**
     * @param array $productSource
     * @return Collection
     */
    private function getAssetCollection(array $productSource)
    {
        $assetCollection = [];

        foreach ($productSource['availableAssets']['asset'] as $asset) {
            if (isset($asset['availableAudioProfiles']['audioFile'])) {
                $audioFile = $asset['availableAudioProfiles']['audioFile'];
                $asset['availableAudioProfiles'] = new Collection('audioFile', [$audioFile]);
            } else {
                $asset['availableAudioProfiles'] = '';
            }

            $assetCollection[] = $asset;
        }

        return new Collection('asset', $assetCollection);
    }
}

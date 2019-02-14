<?php

namespace MovieApps\Service\Browse;

use EbysSdk\Service\Product;
use MovieApps\Response\Collection;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class GetBundleListing implements Service
{
    /**
     * @var Product
     */
    private $sdkProductService;

    /**
     * @param Product $sdkProductService
     */
    public function __construct(Product $sdkProductService)
    {
        $this->sdkProductService = $sdkProductService;
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

        $data = $result->getPayload();

        $payload = Payload::success();
        $payload->bundleTitleID = $params['TitleID'];
        $payload->name = $data['title'];
        $payload->bundleItems = $this->createItems($data);

        return $payload;
    }

    /**
     * @param array $data
     * @return array
     */
    private function createItems(array $data)
    {
        $children = [];
        switch ($data['sub-type']) {
            case 'season':
                $children = $data['episodes'];
                break;
            case 'show':
                $children = $data['seasons'];
                break;
            case 'bundle':
                $children = $data['children'];
                break;
        }

        if (empty($children)) {
            return [];
        }

        $items = [];
        foreach ($children as $child) {
            $items[] = [
                'hasWatched' => 'false',
                'itemNumber' => $child['title_number'],
                'name'       => $child['title'],
                'titleID'    => $child['rmh_id'],
                'm_IsBundle' => ($child['sub-type'] === 'season') ? 'true' : 'false'
            ];
        }

        return new Collection('BundleItem', $items);
    }
}

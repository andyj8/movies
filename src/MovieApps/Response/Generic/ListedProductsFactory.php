<?php

namespace MovieApps\Response\Generic;

use MovieApps\Response\AttributedProperty;
use MovieApps\Response\Collection;

class ListedProductsFactory
{
    /**
     * @param array $result
     * @param array $params
     * @return ListProductsPayload
     */
    public function createPayload(array $result, array $params)
    {
        $items = [];

        foreach ($result as $hit) {
            $item = new ListedProduct();

            $item->commonSense = new AttributedProperty('', ['i:i:nil' => 'true']);
            $item->name = $hit['title'];
            $item->titleID = $hit['rmh_id'];
            $item->boxartPrefix = $hit['image_name'];
            $item->parentBundleTitleID = '0';

            $item->isBundle = in_array($hit['sub-type'], ['bundle', 'show', 'season']) ? 'true' : 'false';
            $item->isInBundle = in_array($hit['sub-type'], ['season', 'episode']) ? 'true' : 'false';

            $items[] = $item;
        }

        $response = new ListProductsPayload('0', 'Success');

        $response->items = new Collection('browseItem', $items);
        $response->itemsPerPage = $params['ItemsPerPage'];
        $response->pageNum = $params['PageNum'];
        $response->profile = $params['Profile'];
        $response->purchaseType = $params['PurchaseType'];
        $response->sort = $params['Sort'];
        $response->genreID = !empty($params['GenreID']) ? $params['GenreID'] : '0';
        $response->totalItems = count($result);

        $totalPages = ceil($response->totalItems / $params['ItemsPerPage']);
        $response->totalPages = ($totalPages == 0) ? 1 : $totalPages;

        return $response;
    }

}

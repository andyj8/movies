<?php

namespace MovieApps\Response\Generic;

use MovieApps\Response\Collection;
use MovieApps\Response\Payload;

class ListProductsPayload extends Payload
{
    /**
     * @var Collection
     */
    public $items;
    
    /**
     * @var int
     */
    public $itemsPerPage;

    /**
     * @var int
     */
    public $pageNum;

    /**
     * @var string
     */
    public $profile;

    /**
     * @var string
     */
    public $purchaseType;

    /**
     * @var string
     */
    public $sort;

    /**
     * @var int
     */
    public $totalItems;

    /**
     * @var int
     */
    public $totalPages;

    /**
     * @var string
     */
    public $genreID;
        
}

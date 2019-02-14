<?php

namespace MovieApps\Response\Generic;

class ListedProduct
{
    /**
     * @var string
     */
    public $boxartPrefix;

    /**
     * @var string
     */
    public $commonSense = '';

    /**
     * @var bool
     */
    public $hasPromo = 'false';

    /**
     * @var bool
     */
    public $hasPromoBadging = 'false';

    /**
     * @var bool
     */
    public $isBundle;

    /**
     * @var bool
     */
    public $isInBundle;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $parentBundleTitleID;

    /**
     * @var string
     */
    public $titleID;
}

<?php

namespace MovieApps\Response;

class Collection
{
    /**
     * @var string
     */
    private $container;

    /**
     * @var array
     */
    private $items;

    /**
     * @param string $container
     * @param array $items
     */
    public function __construct($container, array $items)
    {
        $this->container = $container;
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getItemByKey($key)
    {
        return $this->items[$key];
    }
}

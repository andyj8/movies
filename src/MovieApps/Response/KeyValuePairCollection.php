<?php

namespace MovieApps\Response;

class KeyValuePairCollection
{
    /**
     * @var string
     */
    private $container;

    /**
     * @var string
     */
    private $keyName;

    /**
     * @var string
     */
    private $valueName;

    /**
     * @var array
     */
    private $rows;

    /**
     * @param string $container
     * @param string $keyName
     * @param string $valueName
     */
    public function __construct($container = 'nameAndValue', $keyName = 'keyName', $valueName = 'keyValue')
    {
        $this->container = $container;
        $this->keyName = $keyName;
        $this->valueName = $valueName;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addRow($key, $value)
    {
        $this->rows[$key] = $value;
    }

    /**
     * @return string
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function keyName()
    {
        return $this->keyName;
    }

    /**
     * @return string
     */
    public function valueName()
    {
        return $this->valueName;
    }

    /**
     * @return array
     */
    public function rows()
    {
        return $this->rows;
    }
}

<?php

namespace MovieApps\Response;

class AttributedProperty
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @param string $value
     * @param array $attributes
     */
    public function __construct($value, array $attributes)
    {
        $this->value = $value;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}

<?php

namespace MovieApps\Response\Formatter;

use MovieApps\Response\Collection;
use MovieApps\Response\KeyValuePairCollection;

class JsonFormatter
{
    /**
     * @param $response
     * @return string
     */
    public function formatToJson(array $response)
    {
        $this->convertCollections($response);
        $this->convertKeyValues($response);
        // $this->convertBooleans($response);

        if (count($response) == 1 && isset($response[0])) {
            $response = $response[0];
        }

        $payload = [
            'id' => 1,
            'result' => $response
        ];

        return json_encode($payload, JSON_PRETTY_PRINT);
    }

    /**
     * @param array $json
     */
    private function convertKeyValues(array &$json)
    {
        foreach ($json as $key => $value) {
            if ($value instanceof KeyValuePairCollection) {
                $json[$key] = [];
                foreach ($value->rows() as $k => $v) {
                    $json[$key][] = [
                        $value->keyName()   => $k,
                        $value->valueName() => $v
                    ];
                }
            }
        }
    }

    /**
     * @param array $json
     */
    private function convertCollections(array &$json)
    {
        foreach ($json as $key => $value) {
            if ($value instanceof Collection) {
                if (empty($value->items())) {
                    $json[$key] = '';
                    continue;
                }

                $this->attachCollectionItems($json, $key);
            }
        }
    }

    /**
     * @param array $json
     * @param $key
     */
    private function attachCollectionItems(array &$json, $key)
    {
        /** @var Collection $value */
        $value = $json[$key];
        $json[$key] = [];

        if (count($value->items()) == 1) {
            foreach ($value->getItemByKey(0) as $fieldKey => $fieldValue) {
                $json[$key][$value->container()][$fieldKey] = $fieldValue;
                if ($fieldValue instanceof Collection) {
                    $this->convertCollections($json[$key][$value->container()]);
                }
            }
            return;
        }

        foreach ($value->items() as $itemKey => $item) {
            foreach ($item as $fieldKey => $fieldValue) {
                $json[$key][$value->container()][$itemKey][$fieldKey] = $fieldValue;
                if ($fieldValue instanceof Collection) {
                    $this->convertCollections($json[$key][$value->container()][$itemKey]);
                }
            }
        }
    }

    /**
     * @param array $json
     */
    private function convertBooleans(array &$json)
    {
        foreach ($json as $key => $value) {
            if (is_array($value) ) {
                $this->convertBooleans($json[$key]);
                continue;
            }
            if (!is_string($value)) {
                continue;
            }

            if (strtolower($value) === 'true') {
                $json[$key] = true;
            }
            if (strtolower($value) === 'false') {
                $json[$key] = false;
            }
        }
    }
}

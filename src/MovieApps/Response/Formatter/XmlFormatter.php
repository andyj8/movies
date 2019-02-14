<?php

namespace MovieApps\Response\Formatter;

use MovieApps\Controller\Request;
use MovieApps\Response\AttributedProperty;
use MovieApps\Response\Collection;
use MovieApps\Response\KeyValuePairCollection;
use SimpleXMLElement;

class XmlFormatter
{
    const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    const NS_XSI  = 'http://www.w3.org/2001/XMLSchema-instance';
    const NS_XSD  = 'http://www.w3.org/2001/XMLSchema';
    const NS_WSA  = 'http://schemas.xmlsoap.org/ws/2004/08/addressing';
    const NS_WSSE = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    const NS_WSU  = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    
    /**
     * @param Request $req
     * @param $response
     * @return mixed
     */
    public function formatToXml(Request $req, $response)
    {
        $fatResponse = !in_array($req->endpoint(), ['browse', 'search']);
        $prefix = ($fatResponse) ? 'soap' : 's';

        $envelope = @new SimpleXMLElement('<'. $prefix . ':Envelope />');
        $envelope->addAttribute('xmlns:xmlns:' . $prefix, self::NS_SOAP);

        if ($fatResponse) {
            $envelope->addAttribute('xmlns:xmlns:xsi',  self::NS_XSI);
            $envelope->addAttribute('xmlns:xmlns:xsd',  self::NS_XSD);
            $envelope->addAttribute('xmlns:xmlns:wsa',  self::NS_WSA);
            $envelope->addAttribute('xmlns:xmlns:wsse', self::NS_WSSE);
            $envelope->addAttribute('xmlns:xmlns:wsu',  self::NS_WSU);

            $action = 'http://WebServices/OrbitServices/' . $req->method() . 'Response';
            $header = $envelope->addChild('soap:soap:Header');
            $header->addChild('wsa:wsa:Action', $action);
        }

        $xBody = $envelope->addChild($prefix . ':' . $prefix . ':Body');
        $xResponse = $xBody->addChild($req->method() . 'Response');
        $xResponse->addAttribute('xmlns', 'http://WebServices/OrbitServices');
        $xResult = $xResponse->addChild($req->method() . 'Result');

        $this->convertToXml($response, $xResult);

        return $envelope->asXML();
    }

    /**
     * @param $data
     * @param $xml
     * @return mixed
     */
    private function convertToXml($data, SimpleXMLElement $xml)
    {
        if (is_string($data)) {
            $xml[0] = $data;
            return $xml;
        }

        if ($data instanceof Collection) {
            foreach ($data->items() as $item) {
                $itemNode = $xml->addChild(ucwords($data->container()));
                $this->convertToXml((array) $item, $itemNode);
            }
            return $xml;
        }

        $data = (array) $data;

        foreach ($data as $key => $value) {
            if ($value instanceof Collection) {
                $subNode = $xml->addChild(ucwords($key));
                foreach ($value->items() as $item) {
                    $itemNode = $subNode->addChild(ucwords($value->container()));
                    $this->convertToXml((array) $item, $itemNode);
                }
            } elseif ($value instanceof KeyValuePairCollection) {
                $subNode = $xml->addChild(ucwords($key));
                foreach ($value->rows() as $k => $v) {
                    $rowNode = $subNode->addChild(ucwords($value->container()));
                    $rowNode->addChild(ucwords($value->keyName()), htmlspecialchars($k));
                    $rowNode->addChild(ucwords($value->valueName()), htmlspecialchars($v));
                }
            } else {
                if (!is_null($value)) {
                    $property = $xml->addChild($this->formatKey($key), htmlspecialchars($value));
                    if ($value instanceof AttributedProperty) {
                        foreach ($value->attributes() as $name => $val) {
                            $property->addAttribute($name, $val);
                        }
                    }
                }
            }
        }

        return $xml;
    }

    /**
     * @param $key
     * @return string
     */
    private function formatKey($key)
    {
        $raw = ['m_IsBundle'];

        if (in_array($key, $raw)) {
            return $key;
        }

        return ucwords($key);
    }
}

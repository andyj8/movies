<?php

namespace MovieApps\Controller\Protocol;

use EbysSdk\Storage\StorageInterface;
use MovieApps\Controller\Dispatcher;
use MovieApps\Controller\Request;
use MovieApps\Response\Formatter\XmlFormatter;
use Slim\Http\Request as HttpRequest;
use Slim\Http\Response as HttpResponse;

class SoapController
{
    /**
     * @var StorageInterface
     */
    private $sessionStorage;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * @var XmlFormatter
     */
    private $xmlFormatter;

    /**
     * @param StorageInterface $sessionStorage
     * @param Dispatcher $dispatcher
     * @param XmlFormatter $xmlFormatter
     */
    public function __construct(StorageInterface $sessionStorage, Dispatcher $dispatcher, XmlFormatter $xmlFormatter)
    {
        $this->sessionStorage = $sessionStorage;
        $this->dispatcher = $dispatcher;
        $this->xmlFormatter = $xmlFormatter;
    }

    /**
     * @param $endpoint
     * @param HttpRequest $httpRequest
     * @param HttpResponse $httpResponse
     * @return string
     */
    public function route($endpoint, HttpRequest $httpRequest, HttpResponse $httpResponse)
    {
        $request = $this->createRequest($endpoint, $httpRequest);
        $serviceResponse = $this->dispatcher->dispatch($request);

        $response = $serviceResponse->response;
        if ($serviceResponse->provider == Dispatcher::PROVIDER_ENTS) {
            $response = $this->xmlFormatter->formatToXml($request, $response);
        }

        return $httpResponse
            ->withHeader('Content-Type', 'application/soap+xml;charset=utf-8')
            ->withHeader('Provided-By', $serviceResponse->provider)
            ->write($response);
    }

    /**
     * @param $endpoint
     * @param HttpRequest $httpRequest
     * @return Request
     */
    private function createRequest($endpoint, HttpRequest $httpRequest)
    {
        $soapAction = explode('/', $httpRequest->getHeaderLine('SOAPAction'));
        $method = end($soapAction);

        $children = $httpRequest->getParsedBody()->children('soap', true);
        $params = (array) $children->Body->children()->$method;

        $header = $children->Header->children();
        if ($header->settings) {
            $requestSettings = (array) $header->settings;
        } else {
            $requestSettings = (array) $header->SettingsHeaderWSS;
        }

        $settings = [];
        $settings['DestinationTypeID'] = $requestSettings['DestinationTypeID'];
        $settings['DestinationUniqueID'] = $requestSettings['DestinationUniqueID'];

        if ($httpRequest->hasHeader('ForceAPI')) {
            $settings['ForceAPI'] = $httpRequest->getHeaderLine('ForceAPI');
        }

        $session = null;
        if (isset($requestSettings['AuthToken'])) {
            $session = $this->sessionStorage->get($requestSettings['AuthToken']);
        }

        return new Request(Request::PROTOCOL_SOAP, $endpoint, $method, $params, $settings, $session);
    }
}

<?php

namespace MovieApps\Controller\Protocol;

use EbysSdk\Storage\StorageInterface;
use MovieApps\Controller\Dispatcher;
use MovieApps\Controller\Request;
use MovieApps\Response\Formatter\JsonFormatter;
use Slim\Http\Request as HttpRequest;
use Slim\Http\Response as HttpResponse;

class JsonRpcController
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
     * @var JsonFormatter
     */
    private $jsonFormatter;

    /**
     * @param StorageInterface $sessionStorage
     * @param Dispatcher $dispatcher
     * @param JsonFormatter $jsonFormatter
     */
    public function __construct(StorageInterface $sessionStorage, Dispatcher $dispatcher, JsonFormatter $jsonFormatter)
    {
        $this->sessionStorage = $sessionStorage;
        $this->dispatcher = $dispatcher;
        $this->jsonFormatter = $jsonFormatter;
    }

    /**
     * @param $endpoint
     * @param HttpRequest $httpRequest
     * @param HttpResponse $httpResponse
     * @return mixed
     */
    public function route($endpoint, HttpRequest $httpRequest, HttpResponse $httpResponse)
    {
        $request = $this->createRequest($endpoint, $httpRequest);
        $serviceResponse = $this->dispatcher->dispatch($request);

        $response = $serviceResponse->response;
        if ($serviceResponse->provider == Dispatcher::PROVIDER_ENTS) {
            $response = $this->jsonFormatter->formatToJson((array) $response);
        }
        
        return $httpResponse
            ->withHeader('Content-Type', 'application/json;charset=utf-8')
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
        $body = $httpRequest->getParsedBody();
        
        $settings = [];
        $settings['DestinationTypeID'] = $httpRequest->getHeaderLine('DestinationTypeID');
        $settings['DestinationUniqueID'] = $httpRequest->getHeaderLine('DestinationUniqueID');
        $settings['Accept'] = $httpRequest->getHeaderLine('Accept');
        
        if ($httpRequest->hasHeader('ForceAPI')) {
            $settings['ForceAPI'] = $httpRequest->getHeaderLine('ForceAPI');
        }

        $session = null;
        if ($httpRequest->hasHeader('AuthToken')) {
            $session = $this->sessionStorage->get($httpRequest->getHeaderLine('AuthToken'));
        }

        return new Request(Request::PROTOCOL_JSON, $endpoint, $body['method'], $body['params'], $settings, $session);
    }
}

<?php

namespace MovieApps\Controller;

use MovieApps\Service\ServiceResponse;
use Pimple\Container;

class Dispatcher
{
    const PROVIDER_RMH  = 'rmh';
    const PROVIDER_ENTS = 'ents';

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @return ServiceResponse
     */
    public function dispatch(Request $request)
    {
        $config = $this->container['config.services'][$request->endpoint()];
        $provider = $config['methods'][$request->method()];
        
        if ($request->hasSetting('ForceAPI')) {
            $provider = $request->getSetting('ForceAPI');
        }

        if ($provider === self::PROVIDER_ENTS) {
            return $this->callEnts($request);
        }

        return $this->callRmh($request);
    }

    /**
     * @param Request $request
     * @return ServiceResponse
     */
    private function callEnts(Request $request)
    {
        if ($request->session()) {
            $slapiClient = $this->container['client.slapi'];
            $slapiClient->setAuthToken($request->session()->slapiAuthToken);
        }

        $key = 'service.' . $request->endpoint() . '.' . $request->method();
        $service = $this->container[$key];
        $response = $service->usingEnts($request->params(), $request->settings());

        return new ServiceResponse(self::PROVIDER_ENTS, $response);
    }

    /**
     * @param Request $request
     * @return ServiceResponse
     */
    private function callRmh(Request $request)
    {
        $method = $request->method();
        $rmhEndpoint = $this->container['rmh.' . $request->endpoint()];
        $rmhEndpoint->setProtocol($request->protocol());

        $response = $rmhEndpoint->$method($request->params(), $request->settings());

        return new ServiceResponse(self::PROVIDER_RMH, $response[1]);
    }
}

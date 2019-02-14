<?php

namespace MovieApps\Client;

use Unirest\Request;
use Unirest\Request\Body;

class RestSlapiClient implements SlapiClient
{
    /**
     * @var string
     */
    private $slapiUrl;

    /**
     * @var string
     */
    private $authToken;

    /**
     * @param string $slapiHost
     */
    public function __construct($slapiHost)
    {
        $this->slapiUrl = $slapiHost . '/v1/';

        Request::curlOpt(CURLOPT_SSL_VERIFYHOST, false);
        Request::curlOpt(CURLOPT_SSL_VERIFYPEER, false);
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;
    }

    /**
     * @param $endpoint
     * @param array $data
     * @return \Unirest\Response
     * @throws \Unirest\Exception
     */
    public function post($endpoint, array $data = [])
    {
        $headers = $this->getHeaders();
        $body = Body::Json($data);
        $result = Request::post($this->slapiUrl . $endpoint, $headers, $body);

        return $result;
    }

    /**
     * @param $endpoint
     * @param string $query
     * @return \Unirest\Response
     */
    public function get($endpoint, $query = '')
    {
        $headers = $this->getHeaders();
        $result = Request::get($this->slapiUrl . $endpoint, $headers, $query);

        return $result;
    }

    /**
     * @param $endpoint
     * @return \Unirest\Response
     */
    public function delete($endpoint)
    {
        $headers = $this->getHeaders();
        $result = Request::delete($this->slapiUrl . $endpoint, $headers);

        return $result;
    }

    /**
     * @return array
     */
    private function getHeaders()
    {
        $headers = [
            'Content-Type'     => 'application/json',
            'X-Requested-With' => 'moviesmiddleware'
        ];

        if ($this->authToken) {
            $headers['Authorization'] = 'Bearer ' . $this->authToken;
        }

        return $headers;
    }
}

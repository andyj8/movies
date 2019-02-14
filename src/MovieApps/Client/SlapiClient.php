<?php

namespace MovieApps\Client;

interface SlapiClient
{
    /**
     * @param string $authToken
     */
    public function setAuthToken($authToken);

    /**
     * @param $endpoint
     * @param array $data
     */
    public function post($endpoint, array $data = []);

    /**
     * @param $endpoint
     * @param string $query
     */
    public function get($endpoint, $query = '');

    /**
     * @param $endpoint
     */
    public function delete($endpoint);
}

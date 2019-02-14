<?php

namespace MovieApps\Service\Download;

use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class PollForDownloads implements Service
{
    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $response = Payload::success();
        $response->contentItems = [];

        return $response;
    }
}

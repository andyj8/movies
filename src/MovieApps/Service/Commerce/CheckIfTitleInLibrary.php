<?php

namespace MovieApps\Service\Commerce;

use MovieApps\Client\SlapiClient;
use MovieApps\Service\Service;

class CheckIfTitleInLibrary implements Service
{
    /**
     * @var SlapiClient
     */
    private $slapiClient;

    /**
     * @param SlapiClient $slapiClient
     */
    public function __construct(SlapiClient $slapiClient)
    {
        $this->slapiClient = $slapiClient;
    }
    
    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->slapiClient->get('me/library/video');

        foreach ($result->body->payload as $item) {
            if ($item->titleID == $params['TitleID']) {
                if ($item->purchaseType == 'buy') {
                    return 'true';
                }
                if ($item->purchaseType == 'rent' && !empty($item->minutesToExpire)) {
                    return 'true';
                }
            }
        }

        return 'false';
    }
}

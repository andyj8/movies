<?php

namespace MovieApps\Service\Library;

use DateTime;
use MovieApps\Client\SlapiClient;
use MovieApps\Service\Service;

class GetLastLibraryUpdateTimeUTC implements Service
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

        $updateTime = new DateTime('1900-01-01');

        foreach ($result->body->payload as $item) {
            $this->replaceUpdateTimeWith($updateTime, $item->purchaseDate);
            $this->replaceUpdateTimeWith($updateTime, $item->licenseDate);
            $this->replaceUpdateTimeWith($updateTime, $item->streamLastWatched);
        }

        return $updateTime->format('Y-m-d\TH:i:s.uP');
    }

    /**
     * @param DateTime $updateTime
     * @param $dateString
     */
    private function replaceUpdateTimeWith(DateTime &$updateTime, $dateString)
    {
        if ($dateString) {
            $date = new DateTime($dateString);
            if ($date > $updateTime) {
                $updateTime = $date;
            }
        }
    }
}

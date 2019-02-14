<?php

namespace MovieApps\Service\Util;

use MovieApps\Client\SlapiClient;
use MovieApps\Response\KeyValuePairCollection;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class SetupDevice implements Service
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
        $result = $this->slapiClient->get('me');
        $payload = (array) $result->body->payload;

        $response = Payload::success();
        $response->sessionID = '';

        $config = new KeyValuePairCollection();
        $config->addRow('ParentPinEnabled', 'False');
        $config->addRow('AdultPinEnabled', 'False');
        $config->addRow('PurchasePinEnabled', 'False');
        $config->addRow('ParentalControlsConfigured', 'True');
        $config->addRow('SessionLimitRows', '30');
        $config->addRow('SessionLimitMinutes', '2');
        $config->addRow('AllowAdult', 'False');
        $config->addRow('AllowPlayboy', 'False');
        $config->addRow('RootMoodGenreID', '10995');
        $config->addRow('TVRootMoodGenreID', '11040');
        $config->addRow('RecomendedGenreIds', '10972,10991,11096');
        $config->addRow('ImageLoadTest', 'http://www.cinemanow.com/img/spacer.gif');
        $config->addRow('FlixsterIcon', '');
        $config->addRow('RottentomatoesIcon', '');
        $config->addRow('JinniEnable', 'False');
        $config->addRow('FlixsterEnable', 'True');
        $config->addRow('BaselineEnable', 'True');
        $config->addRow('DolbyEnable', 'false');
        $config->addRow('DTSEnable', 'false');
        $config->addRow('DolbyPlusEnable', 'false');
        $config->addRow('DolbyStereoEnable', 'false');
        $config->addRow('DTSStereoEnable', 'false');
        $config->addRow('Dolby51TestFile', '');
        $config->addRow('DTS51TestFile', '');
        $config->addRow('DolbyStereoTestFile', '');
        $config->addRow('DTSStereoTestFile', '');
        $config->addRow('CountryID', '82');
        $config->addRow('CountryCode', 'gbr');
        $config->addRow('ClientIP', '217.33.2.3');
        $config->addRow('HDSDTransitionStutterTimer', '');
        $config->addRow('HDSDTransitionPauseTimer', '');
        $config->addRow('HDSDTransitionStutterCountDuringMaximumTransitionTimer', '');
        $config->addRow('HDSDTransitionStutterCountDuringMinimumTransitionTimer', '');
        $config->addRow('HDSDTransitionMinimumTransitionTimer', '');
        $config->addRow('HDSDTransitionMaximumTransitionTimer', '');
        $config->addRow('XBOXSupportedPlatforms', '');
        $config->addRow('ShareXboxUserRatingsWithMicrosoft', '');
        $config->addRow('EnableRoaming', 'false');
        $config->addRow('D2DEnabled', '');
        $config->addRow('RegionCode', 'eng');
        $config->addRow('AllowedRegions', 'ab,aol,bc,mb,nb,nf,ns,nt,nu,on,pe,sk,yt,na,qc,nl');
        $config->addRow('LastCacheLoadTimeUTC', '2016-07-21T08:05:29.0000000');
        $config->addRow('ShouldUpdateCache', 'True');
        $config->addRow('AffId', '5422');
        $config->addRow('ActivationURL', 'https://movies.sainsburysentertainment.co.uk/devices/manage');
        $config->addRow('EnableAccountLink', 'False');
        $config->addRow('Account_Link_URL', 'www.roxionow.com/link');
        $config->addRow('DisplayEula', $this->getDisplayEula($payload));
        $config->addRow('ShouldEnableTvNode', 'True');
        $config->addRow('BandwithCheckURL', 'http://d2uh6qw9u54ir8.cloudfront.net/bumper1.vob');
        $config->addRow('MinimumHDNetworkSpeedMBPS', '3.5');
        $config->addRow('BandwithCheckURLSize', '3757608');
        $config->addRow('BandwidthCheckURLSize', '3757608');
        $config->addRow('AuthTokenActive', 'False');
        $config->addRow('PollLibraryUpdateIntervalInSeconds', '2');
        $config->addRow('UVEnabled', 'True');
        $config->addRow('CCEnable', '');
        $config->addRow('ThumbnailUrl', '');
        $config->addRow('MpegDashLicenseServers', '');
        $config->addRow('MpegDashPlaybackSuffixes', '');

        $response->configValues = $config;

        return $response;
    }

    /**
     * @param array $payload
     * @return string
     */
    private function getDisplayEula(array $payload)
    {
        $displayEula = true;

        if (!empty($payload['id'])) {
            $result = $this->slapiClient->get('person/' . $payload['id'] . '/data');
            $payload = (array) $result->body->payload;

            foreach ($payload as $item) {
                if ($item->key == AcceptEula::EULA_ACCEPTED) {
                    $displayEula = empty($item->value);
                }
            }
        }

        return ($displayEula) ? 'True' : 'False';
    }
}

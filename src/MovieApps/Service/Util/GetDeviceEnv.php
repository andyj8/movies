<?php

namespace MovieApps\Service\Util;

use MovieApps\Response\KeyValuePairCollection;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class GetDeviceEnv implements Service
{
    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $response = Payload::success();
        $response->defaultKey = 'www';
        
        $env = new KeyValuePairCollection();
        $env->addRow('stgapi', 'UAT1');
        $env->addRow('stg', 'UAT2');
        $env->addRow('www', '*www Prod Server');
        $env->addRow('ps3', ' PS3 Prod');
        $env->addRow('xbox', ' XBox Prod');
        $env->addRow('stg-cinemanow', 'Staging');
        $env->addRow('dev-cinemanow', 'Dev');
        $env->addRow('orbitv2', 'OrbitV2 Load Balanced');

        $ext = 'asmx';
        $wcfExt = 'svc';
        $uvFile = 'uv';

        if (self::isJson($settings)) {
            $ext = 'ashx';
            $wcfExt = 'ashx';
            $uvFile = 'default';
        }

        $endpoints = new KeyValuePairCollection();
        $endpoints->addRow('auth', 'api/orbit/auth/default.' . $ext);
        $endpoints->addRow('uv_auth', 'api/orbit/auth/uv.' . $ext);
        $endpoints->addRow('browse', 'api/orbit/browse/default.' . $wcfExt);
        $endpoints->addRow('commerce', 'api/orbit/commerce/default.' . $ext);
        $endpoints->addRow('search', 'api/orbit/search/default.' . $wcfExt);
        $endpoints->addRow('titledata', 'api/orbit/titledata/default.' . $wcfExt);
        $endpoints->addRow('util', 'api/orbit/util/default.' . $ext);
        $endpoints->addRow('wishlist', 'api/orbit/wishlist/default.' . $ext);
        $endpoints->addRow('parentalcontrol', 'api/orbit/parentalcontrol/default.' . $wcfExt);
        $endpoints->addRow('user_auth', 'api/orbit/auth/user.' . $ext);
        $endpoints->addRow('library', 'api/orbit/library/' . $uvFile . '.' . $ext);
        $endpoints->addRow('stream', 'api/orbit/stream/' . $uvFile . '.' . $ext);
        $endpoints->addRow('download', 'api/orbit/download/' . $uvFile . '.' . $ext);

        $response->enviroSelectable = $env;
        $response->endPoints = $endpoints;

        return $response;
    }

    /**
     * @param array $settings
     * @return bool
     */
    public static function isJson(array $settings)
    {
        return (isset($settings['Accept']) && ($settings['Accept'] == 'application/json'));
    }
}

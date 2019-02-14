<?php

namespace MovieApps\Service\User;

use MovieApps\Service\Service;

class LoginUserExt implements Service
{
    /**
     * @var LoginUser
     */
    private $loginService;

    /**
     * @param LoginUser $loginService
     */
    public function __construct(LoginUser $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        return $this->loginService->usingEnts($params, $settings);
    }
}

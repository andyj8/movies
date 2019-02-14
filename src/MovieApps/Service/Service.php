<?php

namespace MovieApps\Service;

interface Service
{
    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = []);
}

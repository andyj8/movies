<?php

namespace MovieApps\Service\Util;

use MovieApps\Repository\ContentRepository;
use MovieApps\Service\Service;

class GetPrivacyPolicy implements Service
{
    /**
     * @var ContentRepository
     */
    private $contentRepository;

    /**
     * @param ContentRepository $contentRepository
     */
    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        return $this->contentRepository->getContentByKey('privacy');
    }
}

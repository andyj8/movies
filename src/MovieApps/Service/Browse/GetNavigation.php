<?php

namespace MovieApps\Service\Browse;

use EbysSdk\Service\Genre;
use MovieApps\Response\Collection;
use MovieApps\Response\Payload;
use MovieApps\Service\Service;

class GetNavigation implements Service
{
    /**
     * @var Genre
     */
    private $sdkGenreService;

    /**
     * @param Genre $sdkGenreService
     */
    public function __construct(Genre $sdkGenreService)
    {
        $this->sdkGenreService = $sdkGenreService;
    }

    /**
     * @param array $params
     * @param array $settings
     * @return string
     */
    public function usingEnts(array $params = [], array $settings = [])
    {
        $result = $this->sdkGenreService->getAllByProductType('video');

        $response = Payload::success();

        $items = [];
        $items[] = [
            'iD'         => 1000,
            'isShowcase' => 'false',
            'name'       => 'Content Home',
            'parentId'   => 0,
            'visible'    => 'true'
        ];

        foreach ($result->toArray() as $genre) {
            $items[] = [
                'iD'         => $genre['id'],
                'isShowcase' => 'false',
                'name'       => $genre['name'],
                'parentId'   => $genre['parent_id'] ?: 1000,
                'visible'    => 'true'
            ];
        }

        $response->genres = new Collection('genre', $items);

        return $response;
    }
}

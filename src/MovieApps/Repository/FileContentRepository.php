<?php

namespace MovieApps\Repository;

class FileContentRepository implements ContentRepository
{
    /**
     * @param $key
     * @return string
     */
    public function getContentByKey($key)
    {
        return file_get_contents(__DIR__ . '/../../../resources/content/' . $key . '.txt');
    }
}

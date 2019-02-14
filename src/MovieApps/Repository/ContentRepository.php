<?php

namespace MovieApps\Repository;

interface ContentRepository
{
    /**
     * @param $key
     * @return string
     */
    public function getContentByKey($key);
}

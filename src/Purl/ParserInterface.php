<?php

declare(strict_types=1);

namespace Purl;

interface ParserInterface
{
    /**
     * @param string|Url|null $url
     *
     * @return mixed[]
     */
    public function parseUrl($url) : array;
}

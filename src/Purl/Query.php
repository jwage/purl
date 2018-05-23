<?php

declare(strict_types=1);

namespace Purl;

use function http_build_query;
use function parse_str;

/**
 * Query represents the part of a Url after the question mark (?).
 */
class Query extends AbstractPart
{
    /** @var string|null The original query string. */
    private $query;

    public function __construct(?string $query = null)
    {
        $this->query = $query;
    }


    public function getQuery() : string
    {
        $this->initialize();

        return http_build_query($this->data);
    }

    public function setQuery(string $query) : void
    {
        $this->initialized = false;
        $this->query       = $query;
    }

    public function __toString() : string
    {
        return $this->getQuery();
    }

    protected function doInitialize() : void
    {
        parse_str((string) $this->query, $data);

        $this->data = $data;
    }
}

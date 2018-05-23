<?php

declare(strict_types=1);

namespace Purl;

use function array_map;
use function explode;
use function implode;
use function str_replace;

/**
 * Path represents the part of a Url after the domain suffix and before the hashmark (#).
 */
class Path extends AbstractPart
{
    /** @var string|null The original path string. */
    private $path;

    public function __construct(?string $path = null)
    {
        $this->path = $path;
    }

    public function getPath() : string
    {
        $this->initialize();

        return implode('/', array_map(function ($value) {
            return str_replace(' ', '%20', $value);
        }, $this->data));
    }

    public function setPath(string $path) : void
    {
        $this->initialized = false;
        $this->path        = $path;
    }


    /**
     * @return mixed[]
     */
    public function getSegments() : array
    {
        $this->initialize();

        return $this->data;
    }

    public function __toString() : string
    {
        return $this->getPath();
    }

    protected function doInitialize() : void
    {
        $this->data = explode('/', (string) $this->path);
    }
}

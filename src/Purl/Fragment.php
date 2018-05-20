<?php

declare(strict_types=1);

namespace Purl;

use function array_merge;
use function parse_url;
use function sprintf;

/**
 * Fragment represents the part of a Url after the hashmark (#).
 *
 * @property Path|string $path
 * @property Query|string $query
 */
class Fragment extends AbstractPart
{
    /** @var string|null The original fragment string. */
    private $fragment;

    /** @var mixed[] */
    protected $data = [
        'path'  => null,
        'query' => null,
    ];

    /** @var string[] */
    protected $partClassMap = [
        'path' => 'Purl\Path',
        'query' => 'Purl\Query',
    ];

    /**
     * @param string|Path|null $fragment
     */
    public function __construct($fragment = null, ?Query $query = null)
    {
        if ($fragment instanceof Path) {
            $this->initialized  = true;
            $this->data['path'] = $fragment;
        } else {
            $this->fragment = $fragment;
        }

        $this->data['query'] = $query;
    }

    /**
     * @param mixed $value
     */
    public function set(string $key, $value) : AbstractPart
    {
        $this->initialize();
        $this->data[$key] = $this->preparePartValue($key, $value);

        return $this;
    }

    public function getFragment() : string
    {
        $this->initialize();

        return sprintf('%s%s', $this->path, (string) $this->query !== '' ? '?' . (string) $this->query : '');
    }

    public function setFragment(string $fragment) : AbstractPart
    {
        $this->initialized = false;
        $this->data        = [];
        $this->fragment    = $fragment;

        return $this;
    }

    public function setPath(Path $path) : AbstractPart
    {
        $this->data['path'] = $path;

        return $this;
    }

    public function getPath() : Path
    {
        $this->initialize();

        return $this->data['path'];
    }

    public function setQuery(Query $query) : AbstractPart
    {
        $this->data['query'] = $query;

        return $this;
    }

    public function getQuery() : Query
    {
        $this->initialize();

        return $this->data['query'];
    }

    public function __toString() : string
    {
        return $this->getFragment();
    }

    protected function doInitialize() : void
    {
        if ($this->fragment !== null) {
            $this->data = array_merge($this->data, parse_url($this->fragment));
        }

        foreach ($this->data as $key => $value) {
            $this->data[$key] = $this->preparePartValue($key, $value);
        }
    }
}

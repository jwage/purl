<?php

/*
 * This file is part of the Purl package, a project by Jonathan H. Wage.
 *
 * (c) 2013 Jonathan H. Wage
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Purl;

/**
 * Fragment represents the part of a Url after the hashmark (#).
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 *
 * @property \Purl\Path $path
 * @property \Purl\Query $query
 */
class Fragment extends AbstractPart
{
    /**
     * @var string The original fragment string.
     */
    private $fragment;

    /**
     * @var array
     */
    protected $data = array(
        'path'  => null,
        'query' => null
    );

    /**
     * @var array
     */
    protected $partClassMap = array(
        'path' => 'Purl\Path',
        'query' => 'Purl\Query'
    );

    /**
     * Construct a new Fragment instance.
     *
     * @param string|Path|null $fragment Path instance of string fragment.
     * @param Query|null $query
     */
    public function __construct($fragment = null, Query $query = null)
    {
        if ($fragment instanceof Path) {
            $this->initialized = true;
            $this->data['path'] = $fragment;
        } else {
            $this->fragment = $fragment;
        }
        $this->data['query'] = $query;
    }

    /**
     * @inheritDoc
     * @override
     */
    public function set($key, $value)
    {
        $this->initialize();
        $this->data[$key] = $this->preparePartValue($key, $value);

        return $this;
    }

    /**
     * Builds a string fragment from this Fragment instance internal data and returns it.
     *
     * @return string
     */
    public function getFragment()
    {
        $this->initialize();
        return sprintf('%s%s', $this->path, $this->query->getQuery() ? '?' . $this->query->getQuery() : '');
    }

    /**
     * Set the string fragment for this Fragment instance and sets initialized to false.
     *
     * @param string
     */
    public function setFragment($fragment)
    {
        $this->initialized = false;
        $this->data = array();
        $this->fragment = $fragment;

        return $this;
    }

    /**
     * Set the Path instance.
     *
     * @param Path
     */
    public function setPath(Path $path)
    {
        $this->data['path'] = $path;

        return $this;
    }

    /**
     * Get the Path instance.
     *
     * @return Path
     */
    public function getPath()
    {
        $this->initialize();
        return $this->data['path'];
    }

    /**
     * Set the Query instance.
     *
     * @param Query
     */
    public function setQuery(Query $query)
    {
        $this->data['query'] = $query;

        return $this;
    }

    /**
     * Get the Query instance.
     *
     * @return Query
     */
    public function getQuery()
    {
        $this->initialize();
        return $this->data['query'];
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getFragment();
    }

    /**
     * @inheritDoc
     */
    protected function doInitialize()
    {
        if ($this->fragment) {
            $this->data = array_merge($this->data, parse_url($this->fragment));
        }

        foreach ($this->data as $key => $value) {
            $this->data[$key] = $this->preparePartValue($key, $value);
        }
    }
}
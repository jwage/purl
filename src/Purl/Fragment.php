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
 * @author      Jonathan H. Wage <jonwage@gmail.com>
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
     * Construct a new Fragment instance.
     *
     * @param string $path
     */
    public function __construct($fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * @inheritDoc
     * @override
     */
    public function set($key, $value)
    {
        $this->initialize();
        if ($key === 'path') {
            $value = new Path($value);
        }
        if ($key === 'query') {
            $value = new Query($value);
        }
        $this->data[$key] = $value;
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
        $this->fragment = $fragment;
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
        $this->data = array_merge($this->data, parse_url($this->fragment));

        $this->data['path'] = new Path($this->data['path']);
        $this->data['query'] = new Query($this->data['query']);
    }
}
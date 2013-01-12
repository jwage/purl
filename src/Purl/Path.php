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
 * Path represents the part of a Url after the domain suffix and before the hashmark (#).
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Path extends AbstractPart
{
    /**
     * @var string The original path string.
     */
    private $path;

    /**
     * Construct a new Path instance.
     *
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->path = $path;
    }

    /**
     * Builds a string path from this Path instance internal data and returns it.
     *
     * @return string
     */
    public function getPath()
    {
        $this->initialize();
        return implode('/', array_map(function($value) {
            return str_replace(' ', '%20', $value);
        }, $this->data));
    }

    /**
     * Set the string path for this Path instance and sets initialized to false.
     *
     * @param string
     */
    public function setPath($path)
    {
        $this->initialized = false;
        $this->path = $path;
    }

    /**
     * Get the array of segments that make up the path.
     *
     * @return array
     */
    public function getSegments()
    {
        $this->initialize();
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getPath();
    }

    /**
     * @inheritDoc
     */
    protected function doInitialize()
    {
        $this->data = explode('/', $this->path);
    }
}

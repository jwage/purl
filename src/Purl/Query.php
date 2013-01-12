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
 * Query represents the part of a Url after the question mark (?).
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Query extends AbstractPart
{
    /**
     * @var string The original query string.
     */
    private $query;

    /**
     * Construct a new Query instance.
     *
     * @param string $query
     */
    public function __construct($query = null)
    {
        $this->query = $query;
    }

    /**
     * Builds a string query from this Query instance internal data and returns it.
     *
     * @return string
     */
    public function getQuery()
    {
        $this->initialize();
        return http_build_query($this->data);
    }

    /**
     * Set the string query for this Query instance and sets initialized to false.
     *
     * @param string
     */
    public function setQuery($query)
    {
        $this->initialized = false;
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getQuery();
    }

    /**
     * @inheritDoc
     */
    protected function doInitialize()
    {
        parse_str($this->query);

        $this->data = get_defined_vars();
    }
}

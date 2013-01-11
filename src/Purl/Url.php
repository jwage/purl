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
 * Url is a simple OO class for manipulating Urls in PHP.
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Url extends AbstractPart
{
    /**
     * @var string The original url string.
     */
    private $url;

    /**
     * @var Purl\ParserInterface
     */
    private $parser;

    /**
     * @var array
     */
    protected $data = array(
        'scheme'   => null,
        'host'     => null,
        'port'     => null,
        'user'     => null,
        'pass'     => null,
        'path'     => null,
        'query'    => null,
        'fragment' => null
    );

    /**
     * Construct a new Url instance.
     *
     * @param string $url
     * @param ParserInterface $parser
     */
    public function __construct($url, ParserInterface $parser = null)
    {
        $this->url = $url;
        $this->parser = $parser;
    }

    /**
     * Static convenience method for creating a new Url instance.
     *
     * @param string $url
     */
    public static function parse($url)
    {
        return new self($url);
    }

    /**
     * Gets the ParserInterface instance used to parse this Url instance.
     *
     * @return ParserInterface
     */
    public function getParser()
    {
        if ($this->parser === null) {
            $this->parser = $this->createDefaultParser();
        }

        return $this->parser;
    }

    /**
     * Sets the ParserInterface instance to use to parse this Url instance.
     *
     * @param ParserInterface $parser
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Join this Url instance together with another Url instance or a string url.
     *
     * @param Url|string $url
     * @return Url
     */
    public function join($url)
    {
        $this->initialize();
        $this->data = array_merge($this->data, $this->getParser()->parseUrl($url));

        if (!$this->data['path'] instanceof Path) {
            $this->data['path'] = new Path($this->data['path']);
        }

        if (!$this->data['query'] instanceof Query) {
            $this->data['query'] = new Query($this->data['query']);
        }

        if (!$this->data['fragment'] instanceof Fragment) {
            $this->data['fragment'] = new Fragment($this->data['fragment']);
        }

        return $this;
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
        if ($key === 'fragment') {
            $value = new Fragment($value);
        }
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Gets the netloc part of the Url. It is the user, pass, host and port returned as a string.
     *
     * @return string
     */
    public function getNetloc()
    {
        return ($this->user && $this->pass ? $this->user.($this->pass ? ':'.$this->pass : '').'@' : '').$this->host.($this->port ? ':'.$this->port : '');
    }

    /**
     * Builds a string url from this Url instance internal data and returns it.
     *
     * @return string
     */
    public function getUrl()
    {
        $this->initialize();

        if (!function_exists('http_build_url')) {
            throw new \RuntimeException('http_build_url() function must exist. pecl install pecl_http');
        }

        return http_build_url(array_map(function($value) {
            return (string) $value;
        }, array_filter($this->data, function($value) { return $value; })));
    }

    /**
     * Set the string url for this Url instance and sets initialized to false.
     *
     * @param string
     */
    public function setUrl($url)
    {
        $this->initialized = false;
        $this->url = $url;
    }

    /**
     * Checks if the Url instance is absolute or not.
     *
     * @return boolean
     */
    public function isAbsolute()
    {
        $this->initialize();
        return $this->scheme && $this->host;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getUrl();
    }

    /**
     * @inheritDoc
     */
    protected function doInitialize()
    {
        $this->data = array_merge($this->data, $this->getParser()->parseUrl($this->url));

        $this->data['path'] = new Path($this->data['path']);
        $this->data['query'] = new Query($this->data['query']);
        $this->data['fragment'] = new Fragment($this->data['fragment']);
    }

    /**
     * Creates the default Parser instance to parse urls.
     *
     * @return Parser
     */
    private function createDefaultParser()
    {
        return new Parser();
    }
}

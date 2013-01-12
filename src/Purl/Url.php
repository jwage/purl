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
    protected $data = array();

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
     * @return Url
     */
    public static function parse($url)
    {
        return new self($url);
    }

    /**
     * Extracts urls from a string of text.
     *
     * @param string $string
     * @return array $urls
     */
    public static function extract($string)
    {
        $regex = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        preg_match_all($regex, $string, $matches);
        $urls = array();
        foreach ($matches[0] as $url) {
            $urls[] = self::parse($url);
        }

        return $urls;
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
        $result = $this->getParser()->parseUrl($url);
        foreach ($result as $key => $value) {
            if ($value !== null) {
                $this->data[$key] = $value;
            }
        }

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
        if ($key === 'path' && !$value instanceof Path) {
            $value = new Path($value);
        }
        if ($key === 'query' && !$value instanceof Query) {
            $value = new Query($value);
        }
        if ($key === 'fragment' && !$value instanceof Fragment) {
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
        return self::httpBuildUrl(array_map(function($value) {
            return (string) $value;
        }, $this->data));
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
        $this->data = $this->getParser()->parseUrl($this->url);

        $this->data['path'] = new Path($this->data['path']);
        $this->data['query'] = new Query($this->data['query']);
        $this->data['fragment'] = new Fragment($this->data['fragment']);
    }

    /**
     * Reconstructs a string URL from an array of parts.
     *
     * @param array $parts
     * @return string $url
     */
    private static function httpBuildUrl(array $parts)
    {
        $parts['path'] = ltrim($parts['path'], '/');

        return sprintf('%s://%s%s%s/%s%s%s',
            $parts['scheme'],
            $parts['user'] ? sprintf('%s%s@', $parts['user'], $parts['pass'] ? sprintf(':%s', $parts['pass']) : '') : '',
            $parts['host'],
            $parts['port'] ? sprintf(':%d', $parts['port']) : '',
            $parts['path'] ? $parts['path'] : '',
            $parts['query'] ? '?'.$parts['query'] : '',
            $parts['fragment'] ? '#'.$parts['fragment'] : ''
        );
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

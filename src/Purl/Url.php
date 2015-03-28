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

use Pdp\PublicSuffixListManager;
use Pdp\Parser as PslParser;

/**
 * Url is a simple OO class for manipulating Urls in PHP.
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 *
 * @property string $scheme
 * @property string $host
 * @property integer $port
 * @property string $user
 * @property string $pass
 * @property \Purl\Path $path
 * @property \Purl\Query $query
 * @property \Purl\Fragment $fragment
 * @property string $publicSuffix
 * @property string $registerableDomain
 * @property string $subdomain
 * @property string $canonical
 * @property string $resource
 */
class Url extends AbstractPart
{
    /**
     * @var string The original url string.
     */
    private $url;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var array
     */
    protected $data = array(
        'scheme'             => null,
        'host'               => null,
        'port'               => null,
        'user'               => null,
        'pass'               => null,
        'path'               => null,
        'query'              => null,
        'fragment'           => null,
        'publicSuffix'       => null,
        'registerableDomain' => null,
        'subdomain'          => null,
        'canonical'          => null,
        'resource'           => null
    );

    /**
     * @var array
     */
    protected $partClassMap = array(
        'path' => 'Purl\Path',
        'query' => 'Purl\Query',
        'fragment' => 'Purl\Fragment'
    );

    /**
     * Construct a new Url instance.
     *
     * @param string $url
     * @param ParserInterface $parser
     */
    public function __construct($url = null, ParserInterface $parser = null)
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
     * Creates an Url instance based on data available on $_SERVER variable.
     *
     * @return Url
     */
    public static function fromCurrent()
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';

        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = "$scheme://$host";

        $url = new self($baseUrl);

        if (!empty($_SERVER['REQUEST_URI'])) {
            list($path, $query) = explode('?', $_SERVER['REQUEST_URI'], 2);
            $url->set('path', $path);
            $url->set('query', $query);
        }

        // Only set port if different from default (80 or 443)
        if (!empty($_SERVER['SERVER_PORT'])) {
            $port = $_SERVER['SERVER_PORT'];
            if (($scheme == 'http' && $port != 80) ||
                ($scheme == 'https' && $port != 443)) {
                $url->set('port', $port);
            }
        }

        // Authentication
        if (!empty($_SERVER['PHP_AUTH_USER'])) {
            $url->set('user', $_SERVER['PHP_AUTH_USER']);
            if (!empty($_SERVER['PHP_AUTH_PW'])) {
                $url->set('pass', $_SERVER['PHP_AUTH_PW']);
            }
        }

        return $url;
    }

    /**
     * Gets the ParserInterface instance used to parse this Url instance.
     *
     * @return ParserInterface
     */
    public function getParser()
    {
        if ($this->parser === null) {
            $this->parser = self::createDefaultParser();
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
        $parts = $this->getParser()->parseUrl($url);

        foreach ($parts as $key => $value) {
            if ($value !== null) {
                $this->data[$key] = $value;
            }
        }

        foreach ($this->data as $key => $value) {
            $this->data[$key] = $this->preparePartValue($key, $value);
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
        $this->data[$key] = $this->preparePartValue($key, $value);

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
     * Set the Fragment instance.
     *
     * @param Fragment
     */
    public function setFragment(Fragment $fragment)
    {
        $this->data['fragment'] = $fragment;

        return $this;
    }

    /**
     * Get the Fragment instance.
     *
     * @return Fragment
     */
    public function getFragment()
    {
        $this->initialize();
        return $this->data['fragment'];
    }

    /**
     * Gets the netloc part of the Url. It is the user, pass, host and port returned as a string.
     *
     * @return string
     */
    public function getNetloc()
    {
        $this->initialize();
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
        $this->data = array();
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
        $parts = $this->getParser()->parseUrl($this->url);

        foreach ($parts as $k => $v) {
            if (!isset($this->data[$k])) {
                $this->data[$k] = $v;
            }
        }

        foreach ($this->data as $key => $value) {
            $this->data[$key] = $this->preparePartValue($key, $value);
        }
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
    private static function createDefaultParser()
    {
        $pslManager = new PublicSuffixListManager(dirname(dirname(__DIR__)) . '/data');
        $pslParser = new PslParser($pslManager->getList());
        
        return new Parser($pslParser);
    }
}

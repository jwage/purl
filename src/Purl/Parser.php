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

use Pdp\Parser as PslParser;

/**
 * Parser class.
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Parser implements ParserInterface
{

    /**
     * @var PslParser Public Suffix List parser
     */
    private $pslParser;

    private static $defaultParts = array(
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
     * Public constructor
     *
     * @param PslParser $pslParser Public Suffix List parser
     */
    public function __construct(PslParser $pslParser)
    {
        $this->pslParser = $pslParser;
    }

    /**
     * @inheritDoc
     */
    public function parseUrl($url)
    {
        $url = (string) $url;

        $parsedUrl = $this->doParseUrl($url);

        if ($parsedUrl === false) {
            throw new \InvalidArgumentException(sprintf('Invalid url %s', $url));
        }

        $parsedUrl = array_merge(self::$defaultParts, $parsedUrl);

        if (isset($parsedUrl['host'])) {
            $parsedUrl['publicSuffix'] = $this->pslParser->getPublicSuffix($parsedUrl['host']);
            $parsedUrl['registerableDomain'] = $this->pslParser->getRegisterableDomain($parsedUrl['host']);
            $parsedUrl['subdomain'] = $this->pslParser->getSubdomain($parsedUrl['host']);
            $parsedUrl['canonical'] = implode('.', array_reverse(explode('.', $parsedUrl['host']))).(isset($parsedUrl['path']) ? $parsedUrl['path'] : '').(isset($parsedUrl['query']) ? '?'.$parsedUrl['query'] : '');

            $parsedUrl['resource'] = isset($parsedUrl['path']) ? $parsedUrl['path'] : '';

            if (isset($parsedUrl['query'])) {
                $parsedUrl['resource'] .= '?'.$parsedUrl['query'];
            }
        }

        return $parsedUrl;
    }

    /**
     * @param string $url
     *
     * @return array $parsedUrl
     */
    protected function doParseUrl($url)
    {
        // If there's a single leading forward slash, use parse_url()
        if (preg_match('#^\/{1}[^\/]#', $url) === 1) { 
            return parse_url($url);
        } else {
            // Otherwise use the PSL parser
            return $this->pslParser->parseUrl($url)->toArray();
        }
    }
}

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

    private static $effectiveTldNames = array();

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

    public function parseUrl($url)
    {
        if ($url instanceof Url) {
            $url = (string) $url;
        }

        $result = parse_url($url);

        if ($result === false) {
            throw new \InvalidArgumentException(sprintf('Invalid url %s', $url));
        }

        $result = array_merge(self::$defaultParts, $result);

        if (isset($result['host'])) {
            $result['publicSuffix'] = $this->pslParser->getPublicSuffix($result['host']);
            $result['registerableDomain'] = $this->pslParser->getRegisterableDomain($result['host']);
            $result['subdomain'] = $this->pslParser->getSubdomain($result['host']);
            $result['canonical'] = implode('.', array_reverse(explode('.', $result['host']))).(isset($result['path']) ? $result['path'] : '').(isset($result['query']) ? '?'.$result['query'] : '');

            $result['resource'] = isset($result['path']) ? $result['path'] : '';

            if (isset($result['query'])) {
                $result['resource'] .= '?'.$result['query'];
            }
        }

        return $result;
    }

}

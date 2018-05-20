<?php

declare(strict_types=1);

namespace Purl;

use Pdp\Parser as PslParser;
use function array_merge;
use function array_reverse;
use function explode;
use function implode;
use function parse_url;
use function preg_match;
use function sprintf;

/**
 * Parser class.
 */
class Parser implements ParserInterface
{
    /** @var PslParser Public Suffix List parser */
    private $pslParser;

    /** @var mixed[] */
    private static $defaultParts = [
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
        'resource'           => null,
    ];

    public function __construct(PslParser $pslParser)
    {
        $this->pslParser = $pslParser;
    }

    /**
     * @param Url|string $url
     *
     * @return mixed[]
     */
    public function parseUrl($url) : array
    {
        $url = (string) $url;

        $parsedUrl = $this->doParseUrl($url);

        if ($parsedUrl === []) {
            throw new \InvalidArgumentException(sprintf('Invalid url %s', $url));
        }

        $parsedUrl = array_merge(self::$defaultParts, $parsedUrl);

        if (isset($parsedUrl['host'])) {
            $parsedUrl['publicSuffix']       = $this->pslParser->getPublicSuffix($parsedUrl['host']);
            $parsedUrl['registerableDomain'] = $this->pslParser->getRegisterableDomain($parsedUrl['host']);
            $parsedUrl['subdomain']          = $this->pslParser->getSubdomain($parsedUrl['host']);
            $parsedUrl['canonical']          = implode('.', array_reverse(explode('.', $parsedUrl['host']))) . ($parsedUrl['path'] ?? '') . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');

            $parsedUrl['resource'] = $parsedUrl['path'] ?? '';

            if (isset($parsedUrl['query'])) {
                $parsedUrl['resource'] .= '?' . $parsedUrl['query'];
            }
        }

        return $parsedUrl;
    }

    /**
     * @return mixed[]
     */
    protected function doParseUrl(string $url) : array
    {
        // If there's a single leading forward slash, use parse_url()
        // Expected matches:
        //
        // "/one/two"   YES
        // "/"          YES PLEASE
        // "//"         NO
        // "//one/two"  NO
        // ""           HELL NO
        if (preg_match('#^[\/]([^\/]|$)#', $url) === 1) {
            $parsedUrl = parse_url($url);

            return $parsedUrl !== false ? $parsedUrl : [];
        }

        // Otherwise use the PSL parser
        return $this->pslParser->parseUrl($url)->toArray();
    }
}

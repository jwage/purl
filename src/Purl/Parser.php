<?php

declare(strict_types=1);

namespace Purl;

use InvalidArgumentException;
use function array_merge;
use function array_reverse;
use function explode;
use function implode;
use function parse_url;
use function sprintf;

/**
 * Parser class.
 */
class Parser implements ParserInterface
{
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
        'canonical'          => null,
        'resource'           => null,
    ];

    /**
     * @param string|Url|null $url
     *
     * @return mixed[]
     */
    public function parseUrl($url) : array
    {
        $url = (string) $url;

        $parsedUrl = $this->doParseUrl($url);

        if ($parsedUrl === []) {
            throw new InvalidArgumentException(sprintf('Invalid url %s', $url));
        }

        $parsedUrl = array_merge(self::$defaultParts, $parsedUrl);

        if (isset($parsedUrl['host'])) {
            $parsedUrl['canonical'] = implode('.', array_reverse(explode('.', $parsedUrl['host']))) . ($parsedUrl['path'] ?? '') . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');

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
        $parsedUrl = parse_url($url);

        return $parsedUrl !== false ? $parsedUrl : [];
    }
}

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
 * Parser class.
 *
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Parser implements ParserInterface
{
    private static $effectiveTldNames = array();

    private static $defaultParts = array(
        'scheme'    => null,
        'host'      => null,
        'port'      => null,
        'user'      => null,
        'pass'      => null,
        'path'      => null,
        'query'     => null,
        'fragment'  => null,
        'suffix'    => null,
        'domain'    => null,
        'subdomain' => null,
        'canonical' => null,
        'resource'  => null
    );


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
            if (!self::$effectiveTldNames) {
                self::readDataFile();
            }

            $parts = array_reverse(explode('.', $result['host']));
            $suffix = array();
            $everythingElse = array();

            foreach ($parts as $i => $part) {
                if (isset(self::$effectiveTldNames[$part])) {
                    $suffix[] = $part;
                } else {
                    $everythingElse[] = $part;
                }
            }

            $result['suffix'] = implode('.', array_reverse($suffix));
            $result['domain'] = array_shift($everythingElse);
            $result['subdomain'] = implode('.', array_reverse($everythingElse));
            $result['canonical'] = implode('.', $parts).(isset($result['path']) ? $result['path'] : '').(isset($result['query']) ? '?'.$result['query'] : '');

            $result['resource'] = isset($result['path']) ? $result['path'] : '';

            if (isset($result['query'])) {
                $result['resource'] .= '?'.$result['query'];
            }
        }

        return $result;
    }

    private static function readDataFile()
    {
        self::$effectiveTldNames = array();

        $handle = @fopen(__DIR__.'/../../data/effective_tld_names.dat', 'r');
        if ($handle) {
            while (!feof($handle)) {
                $line = fgets($handle, 4096);
                $line = trim($line);
                if ($line && !preg_match('/\/\//', $line)) {
                    $parts = array_reverse(explode('.', $line));
                    foreach ($parts as $part) {
                        self::$effectiveTldNames[$part] = array();
                    }
                }
            }

            fclose($handle);
        }
    }
}

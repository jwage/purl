<?php

namespace Purl\Test;

use PHPUnit_Framework_TestCase;
use Purl\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testParseUrl()
    {
        $parser = new Parser();
        $parts = $parser->parseUrl('https://sub.domain.jwage.com:443/about?param=value#fragment?param=value');
        $this->assertEquals(array(
            'scheme' => 'https',
            'host' => 'sub.domain.jwage.com',
            'port' => '443',
            'user' => null,
            'pass' => null,
            'path' => '/about',
            'query' => 'param=value',
            'fragment' => 'fragment?param=value',
            'suffix' => 'com',
            'domain' => 'jwage',
            'subdomain' => 'sub.domain',
            'canonical' => 'com.jwage.domain.sub/about?param=value',
            'resource' => '/about?param=value'
        ), $parts);
    }
}

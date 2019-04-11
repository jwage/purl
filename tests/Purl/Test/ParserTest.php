<?php

declare(strict_types=1);

namespace Purl\Test;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Purl\Parser;

class ParserTest extends TestCase
{
    /** @var Parser */
    private $parser;

    protected function setUp() : void
    {
        parent::setUp();

        $this->parser = new Parser();
    }

    protected function tearDown() : void
    {
        $this->parser = null;
        parent::tearDown();
    }

    public function testParseUrl() : void
    {
        $parts = $this->parser->parseUrl('https://sub.domain.jwage.com:443/about?param=value#fragment?param=value');
        $this->assertEquals([
            'scheme' => 'https',
            'host' => 'sub.domain.jwage.com',
            'port' => 443,
            'user' => null,
            'pass' => null,
            'path' => '/about',
            'query' => 'param=value',
            'fragment' => 'fragment?param=value',
            'canonical' => 'com.jwage.domain.sub/about?param=value',
            'resource' => '/about?param=value',
        ], $parts);
    }

    public function testParseBadUrlThrowsInvalidArgumentException() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid url http:///example.com');

        $this->parser->parseUrl('http:///example.com/one/two?one=two#value');
    }
}

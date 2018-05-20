<?php

declare(strict_types=1);

namespace Purl\Test;

use Pdp\Parser as PslParser;
use Pdp\PublicSuffixListManager;
use PHPUnit\Framework\TestCase;
use Purl\Parser;
use function dirname;

class ParserTest extends TestCase
{
    /** @var Parser */
    private $parser;

    protected function setUp() : void
    {
        parent::setUp();
        $pslManager   = new PublicSuffixListManager(dirname(dirname(dirname(__DIR__))) . '/data');
        $pslParser    = new PslParser($pslManager->getList());
        $this->parser = new Parser($pslParser);
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
            'publicSuffix' => 'com',
            'registerableDomain' => 'jwage.com',
            'subdomain' => 'sub.domain',
            'canonical' => 'com.jwage.domain.sub/about?param=value',
            'resource' => '/about?param=value',
        ], $parts);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid url http:///example.com
     */
    public function testParseBadUrlThrowsInvalidArgumentException() : void
    {
        $this->parser->parseUrl('http:///example.com/one/two?one=two#value');
    }
}

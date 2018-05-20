<?php

declare(strict_types=1);

namespace Purl\Test;

use PHPUnit\Framework\TestCase;
use Purl\Path;

class PathTest extends TestCase
{
    public function testConstruct() : void
    {
        $path = new Path('test');
        $this->assertEquals('test', $path->getPath());
    }

    public function testGetSetPath() : void
    {
        $path = new Path();
        $this->assertEquals('', $path->getPath());
        $path->setPath('test');
        $this->assertEquals('test', $path->getPath());
    }

    public function testGetSegments() : void
    {
        $path = new Path('about/me');
        $this->assertEquals(['about', 'me'], $path->getSegments());
    }

    public function testToString() : void
    {
        $path = new Path('about/me');
        $this->assertEquals('about/me', (string) $path);
    }
}

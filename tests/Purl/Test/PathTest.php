<?php

namespace Purl\Test;

use PHPUnit_Framework_TestCase;
use Purl\Path;

class PathTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $path = new Path('test');
        $this->assertEquals('test', $path->getPath());
    }

    public function testGetSetPath()
    {
        $path = new Path();
        $this->assertEquals('', $path->getPath());
        $path->setPath('test');
        $this->assertEquals('test', $path->getPath());
    }

    public function testGetSegments()
    {
        $path = new Path('about/me');
        $this->assertEquals(array('about', 'me'), $path->getSegments());
    }

    public function testToString()
    {
        $path = new Path('about/me');
        $this->assertEquals('about/me', (string) $path);
    }
}

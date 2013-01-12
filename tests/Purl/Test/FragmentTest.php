<?php

namespace Purl\Test;

use PHPUnit_Framework_TestCase;
use Purl\Fragment;
use Purl\Path;
use Purl\Query;

class FragmentTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $fragment = new Fragment('test?param=value');
        $this->assertInstanceOf('Purl\Path', $fragment->path);
        $this->assertInstanceOf('Purl\Query', $fragment->query);
        $this->assertEquals('test', (string) $fragment->path);
        $this->assertEquals('param=value', (string) $fragment->query);

        $path = new Path('test');
        $query = new Query('param=value');
        $fragment = new Fragment($path, $query);
        $this->assertEquals('test', (string) $fragment->path);
        $this->assertEquals('param=value', (string) $fragment->query);
    }

    public function testGetFragment()
    {
        $fragment = new Fragment('test?param=value');
        $this->assertEquals('test?param=value', $fragment->getFragment());
    }

    public function testSetFragment()
    {
        $fragment = new Fragment('test?param=value');
        $this->assertEquals('test?param=value', $fragment->getFragment());
        $fragment->setFragment('changed?param=value');
        $this->assertEquals('changed?param=value', $fragment->getFragment());
    }

    public function testGetSetPath()
    {
        $fragment = new Fragment();
        $path = new Path('test');
        $fragment->setPath($path);
        $this->assertSame($path, $fragment->getPath());
        $this->assertEquals('test', (string) $fragment);
    }

    public function testGetSetQuery()
    {
        $fragment = new Fragment();
        $query = new Query('param=value');
        $fragment->setQuery($query);
        $this->assertSame($query, $fragment->getQuery());
        $this->assertEquals('?param=value', (string) $fragment);
    }

    public function testToString()
    {
        $fragment = new Fragment('test?param=value');
        $this->assertEquals('test?param=value', (string) $fragment);
    }
}

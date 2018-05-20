<?php

declare(strict_types=1);

namespace Purl\Test;

use PHPUnit\Framework\TestCase;
use Purl\Fragment;
use Purl\Path;
use Purl\Query;

class FragmentTest extends TestCase
{
    public function testConstruct() : void
    {
        $fragment = new Fragment('test?param=value');
        $this->assertInstanceOf('Purl\Path', $fragment->path);
        $this->assertInstanceOf('Purl\Query', $fragment->query);
        $this->assertEquals('test', (string) $fragment->path);
        $this->assertEquals('param=value', (string) $fragment->query);

        $path     = new Path('test');
        $query    = new Query('param=value');
        $fragment = new Fragment($path, $query);
        $this->assertEquals('test', (string) $fragment->path);
        $this->assertEquals('param=value', (string) $fragment->query);
    }

    public function testGetFragment() : void
    {
        $fragment = new Fragment('test?param=value');
        $this->assertEquals('test?param=value', $fragment->getFragment());
    }

    public function testSetFragment() : void
    {
        $fragment = new Fragment('test?param=value');
        $this->assertEquals('test?param=value', $fragment->getFragment());
        $fragment->setFragment('changed?param=value');
        $this->assertEquals('changed?param=value', $fragment->getFragment());
    }

    public function testGetSetPath() : void
    {
        $fragment = new Fragment();
        $path     = new Path('test');
        $fragment->setPath($path);
        $this->assertSame($path, $fragment->getPath());
        $this->assertEquals('test', (string) $fragment);
    }

    public function testGetSetQuery() : void
    {
        $fragment = new Fragment();
        $query    = new Query('param=value');
        $fragment->setQuery($query);
        $this->assertSame($query, $fragment->getQuery());
        $this->assertEquals('?param=value', (string) $fragment);
    }

    public function testToString() : void
    {
        $fragment = new Fragment('test?param=value');
        $this->assertEquals('test?param=value', (string) $fragment);
    }

    public function testIsInitialized() : void
    {
        $fragment = new Fragment('test?param=value');
        $this->assertFalse($fragment->isInitialized());
    }

    public function testHas() : void
    {
        $fragment = new Fragment('test?param=value');
        $fragment->setData(['param' => 'value']);
        $this->assertTrue($fragment->has('param'));
    }

    public function testRemove() : void
    {
        $fragment = new Fragment('test?param=value');
        $fragment->setData(['param' => 'value']);
        $fragment->remove('param');
        $this->assertFalse($fragment->has('param'));
    }

    public function testIsset() : void
    {
        $fragment = new Fragment('test?param=value');
        $fragment->setData(['param' => 'value']);
        $fragment->remove('param');
        $this->assertFalse($fragment->has('param'));
    }

    public function testOffsetExists() : void
    {
        $fragment = new Fragment('test?param=value');
        $fragment->setData(['param' => 'value']);
        $this->assertTrue($fragment->offsetExists('param'));
    }

    public function testOffsetGet() : void
    {
        $fragment = new Fragment('test?param=value');
        $fragment->setData(['param' => 'value']);
        $this->assertEquals('value', $fragment->offsetGet('param'));
    }

    public function testOffsetUnset() : void
    {
        $fragment = new Fragment('test?param=value');
        $fragment->setData(['param' => 'value']);
        $fragment->offsetUnset('param');
        $this->assertFalse($fragment->offsetExists('param'));
    }
}

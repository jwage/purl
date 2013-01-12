<?php

namespace Purl\Test;

use PHPUnit_Framework_TestCase;
use Purl\Query;

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $query = new Query('param=value');
        $this->assertEquals('param=value', $query->getQuery());
    }

    public function testGetSetQuery()
    {
        $query = new Query();
        $this->assertEquals('', $query->getQuery());
        $query->setQuery('param1=value1&param2=value2');
        $this->assertEquals('param1=value1&param2=value2', $query->getQuery());
    }

    public function testToString()
    {
        $query = new Query('param1=value1&param2=value2');
        $this->assertEquals('param1=value1&param2=value2', (string) $query);
    }

    public function testGetSetData()
    {
        $query = new Query('param1=value1&param2=value2');
        $this->assertEquals(array('param1' => 'value1', 'param2' => 'value2'), $query->getData());
        $query->setData(array('param' => 'value'));
        $this->assertEquals('param=value', $query->getQuery());
    }
}

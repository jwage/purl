<?php

declare(strict_types=1);

namespace Purl\Test;

use PHPUnit\Framework\TestCase;
use Purl\Query;

class QueryTest extends TestCase
{
    public function testConstruct() : void
    {
        $query = new Query('param=value');
        $this->assertEquals('param=value', $query->getQuery());
    }

    public function testGetSetQuery() : void
    {
        $query = new Query();
        $this->assertEquals('', $query->getQuery());
        $query->setQuery('param1=value1&param2=value2');
        $this->assertEquals('param1=value1&param2=value2', $query->getQuery());
    }

    public function testToString() : void
    {
        $query = new Query('param1=value1&param2=value2');
        $this->assertEquals('param1=value1&param2=value2', (string) $query);
    }

    public function testGetSetData() : void
    {
        $query = new Query('param1=value1&param2=value2');
        $this->assertEquals(['param1' => 'value1', 'param2' => 'value2'], $query->getData());
        $query->setData(['param' => 'value']);
        $this->assertEquals('param=value', $query->getQuery());
    }
}

<?php

namespace Purl\Test;

use PHPUnit_Framework_TestCase;
use Purl\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        $loader = Autoloader::register();
        $this->assertTrue(spl_autoload_unregister(array($loader, 'autoload')));
    }

    public function testAutoloader()
    {
        $loader = new Autoloader(dirname(__FILE__) . '/../../fixtures/autoloader');

        $this->assertNull($loader->autoload('NonPurlClass'));
        $this->assertFalse(class_exists('NonPurlClass'));

        $loader->autoload('Purl\Foo');
        $this->assertTrue(class_exists('Purl\Foo'));

        // Test with a starting slash
        $loader->autoload('\Purl\Bar');
        $this->assertTrue(class_exists('\Purl\Bar'));
    }
}
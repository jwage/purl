<?php

namespace Purl\Test;

use PHPUnit_Framework_TestCase;
use Purl\Url;

class PublicSuffixListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testPublicSuffixListImplementation($url, $publicSuffix, $registerableDomain, $subdomain, $hostPart)
    {
        $url = new Url($url);
        $this->assertEquals($subdomain, $url->subdomain);
        $this->assertEquals($registerableDomain, $url->registerableDomain);
        $this->assertEquals($publicSuffix, $url->publicSuffix);
    }

    public function parseDataProvider()
    {
        return array(
            array('http://www.waxaudio.com.au/audio/albums/the_mashening', 'com.au', 'waxaudio.com.au', 'www', 'www.waxaudio.com.au'),
            array('example.COM', 'com', 'example.com', null, 'example.com'),
            array('giant.yyyy', 'yyyy', 'giant.yyyy', null, 'giant.yyyy'),
            array('cea-law.co.il', 'co.il', 'cea-law.co.il', null, 'cea-law.co.il'),
            array('http://edition.cnn.com/WORLD/', 'com', 'cnn.com', 'edition', 'edition.cnn.com'),
            array('http://en.wikipedia.org/', 'org', 'wikipedia.org', 'en', 'en.wikipedia.org'),
            array('a.b.c.cy', 'c.cy', 'b.c.cy', 'a', 'a.b.c.cy'),
            array('https://test.k12.ak.us', 'k12.ak.us', 'test.k12.ak.us', null, 'test.k12.ak.us'),
            array('www.scottwills.co.uk', 'co.uk', 'scottwills.co.uk', 'www', 'www.scottwills.co.uk'),
            array('b.ide.kyoto.jp', 'ide.kyoto.jp', 'b.ide.kyoto.jp', null, 'b.ide.kyoto.jp'),
            array('a.b.example.uk.com', 'uk.com', 'example.uk.com', 'a.b', 'a.b.example.uk.com'),
            array('test.nic.ar', 'ar', 'nic.ar', 'test', 'test.nic.ar'),
            array('a.b.test.ck', 'test.ck', 'b.test.ck', 'a', 'a.b.test.ck'),
            array('baez.songfest.om', 'om', 'songfest.om', 'baez', 'baez.songfest.om'),
            array('politics.news.omanpost.om', 'om', 'omanpost.om', 'politics.news', 'politics.news.omanpost.om'),
            array('us.example.com', 'com', 'example.com', 'us', 'us.example.com'),
            array('us.example.na', 'na', 'example.na', 'us', 'us.example.na'),
            array('www.example.us.na', 'us.na', 'example.us.na', 'www', 'www.example.us.na'),
            array('us.example.org', 'org', 'example.org', 'us', 'us.example.org'),
            array('webhop.broken.biz', 'biz', 'broken.biz', 'webhop', 'webhop.broken.biz'),
            array('www.broken.webhop.biz', 'webhop.biz', 'broken.webhop.biz', 'www', 'www.broken.webhop.biz'),
            array('//www.broken.webhop.biz', 'webhop.biz', 'broken.webhop.biz', 'www', 'www.broken.webhop.biz'),
            array('ftp://www.waxaudio.com.au/audio/albums/the_mashening', 'com.au', 'waxaudio.com.au', 'www', 'www.waxaudio.com.au'),
            array('ftps://test.k12.ak.us', 'k12.ak.us', 'test.k12.ak.us', null, 'test.k12.ak.us'),
            array('http://localhost', null, null, null, 'localhost'),
            array('test.museum', 'museum', 'test.museum', null, 'test.museum'),
            array('bob.smith.name', 'name', 'smith.name', 'bob', 'bob.smith.name'),
            array('tons.of.info', 'info', 'of.info', 'tons', 'tons.of.info'),
        );
    }
}

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
            array('http://example.com', 'com', 'example.com', null, 'example.com'),
            array('http://giant.yyyy', 'yyyy', 'giant.yyyy', null, 'giant.yyyy'),
            array('http://cea-law.co.il', 'co.il', 'cea-law.co.il', null, 'cea-law.co.il'),
            array('http://edition.cnn.com/WORLD/', 'com', 'cnn.com', 'edition', 'edition.cnn.com'),
            array('http://en.wikipedia.org/', 'org', 'wikipedia.org', 'en', 'en.wikipedia.org'),
            array('http://a.b.c.cy', 'c.cy', 'b.c.cy', 'a', 'a.b.c.cy'),
            array('https://test.k12.ak.us', 'k12.ak.us', 'test.k12.ak.us', null, 'test.k12.ak.us'),
            array('http://www.scottwills.co.uk', 'co.uk', 'scottwills.co.uk', 'www', 'www.scottwills.co.uk'),
            array('http://b.ide.kyoto.jp', 'ide.kyoto.jp', 'b.ide.kyoto.jp', null, 'b.ide.kyoto.jp'),
            array('http://a.b.example.uk.com', 'uk.com', 'example.uk.com', 'a.b', 'a.b.example.uk.com'),
            array('http://test.nic.ar', 'ar', 'nic.ar', 'test', 'test.nic.ar'),
            array('http://a.b.test.om', 'test.om', 'b.test.om', 'a', 'a.b.test.om'),
            array('http://baez.songfest.om', 'om', 'songfest.om', 'baez', 'baez.songfest.om'),
            array('http://politics.news.omanpost.om', 'om', 'omanpost.om', 'politics.news', 'politics.news.omanpost.om'),
        );
    }
}

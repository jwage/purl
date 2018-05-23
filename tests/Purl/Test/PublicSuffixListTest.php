<?php

declare(strict_types=1);

namespace Purl\Test;

use PHPUnit\Framework\TestCase;
use Purl\Url;

class PublicSuffixListTest extends TestCase
{
    /**
     * @dataProvider parseDataProvider
     */
    public function testPublicSuffixListImplementation(
        string $url,
        ?string $publicSuffix,
        ?string $registerableDomain,
        ?string $subdomain
    ) : void {
        $url = new Url($url);
        $this->assertEquals($subdomain, $url->subdomain);
        $this->assertEquals($registerableDomain, $url->registerableDomain);
        $this->assertEquals($publicSuffix, $url->publicSuffix);
    }

    /**
     * @return mixed[]
     */
    public function parseDataProvider() : array
    {
        return [
            ['http://www.waxaudio.com.au/audio/albums/the_mashening', 'com.au', 'waxaudio.com.au', 'www', 'www.waxaudio.com.au'],
            ['example.COM', 'com', 'example.com', null, 'example.com'],
            ['giant.yyyy', 'yyyy', 'giant.yyyy', null, 'giant.yyyy'],
            ['cea-law.co.il', 'co.il', 'cea-law.co.il', null, 'cea-law.co.il'],
            ['http://edition.cnn.com/WORLD/', 'com', 'cnn.com', 'edition', 'edition.cnn.com'],
            ['http://en.wikipedia.org/', 'org', 'wikipedia.org', 'en', 'en.wikipedia.org'],
            ['a.b.c.cy', 'c.cy', 'b.c.cy', 'a', 'a.b.c.cy'],
            ['https://test.k12.ak.us', 'k12.ak.us', 'test.k12.ak.us', null, 'test.k12.ak.us'],
            ['www.scottwills.co.uk', 'co.uk', 'scottwills.co.uk', 'www', 'www.scottwills.co.uk'],
            ['b.ide.kyoto.jp', 'ide.kyoto.jp', 'b.ide.kyoto.jp', null, 'b.ide.kyoto.jp'],
            ['a.b.example.uk.com', 'uk.com', 'example.uk.com', 'a.b', 'a.b.example.uk.com'],
            ['test.nic.ar', 'ar', 'nic.ar', 'test', 'test.nic.ar'],
            ['a.b.test.ck', 'test.ck', 'b.test.ck', 'a', 'a.b.test.ck'],
            ['baez.songfest.om', 'om', 'songfest.om', 'baez', 'baez.songfest.om'],
            ['politics.news.omanpost.om', 'om', 'omanpost.om', 'politics.news', 'politics.news.omanpost.om'],
            ['us.example.com', 'com', 'example.com', 'us', 'us.example.com'],
            ['us.example.na', 'na', 'example.na', 'us', 'us.example.na'],
            ['www.example.us.na', 'us.na', 'example.us.na', 'www', 'www.example.us.na'],
            ['us.example.org', 'org', 'example.org', 'us', 'us.example.org'],
            ['webhop.broken.biz', 'biz', 'broken.biz', 'webhop', 'webhop.broken.biz'],
            ['www.broken.webhop.biz', 'webhop.biz', 'broken.webhop.biz', 'www', 'www.broken.webhop.biz'],
            ['//www.broken.webhop.biz', 'webhop.biz', 'broken.webhop.biz', 'www', 'www.broken.webhop.biz'],
            ['ftp://www.waxaudio.com.au/audio/albums/the_mashening', 'com.au', 'waxaudio.com.au', 'www', 'www.waxaudio.com.au'],
            ['ftps://test.k12.ak.us', 'k12.ak.us', 'test.k12.ak.us', null, 'test.k12.ak.us'],
            ['http://localhost', null, null, null, 'localhost'],
            ['test.museum', 'museum', 'test.museum', null, 'test.museum'],
            ['bob.smith.name', 'name', 'smith.name', 'bob', 'bob.smith.name'],
            ['tons.of.info', 'info', 'of.info', 'tons', 'tons.of.info'],
        ];
    }
}

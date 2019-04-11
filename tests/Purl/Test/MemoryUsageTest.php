<?php

declare(strict_types=1);

namespace Purl\Test;

use PHPUnit\Framework\TestCase;
use Purl\Url;
use function floor;
use function log;
use function memory_get_usage;
use function pow;
use function round;

class MemoryUsageTest extends TestCase
{
    public function testMemoryUsage() : void
    {
        $domains = [
            'http://google.de',
            'http://google.com',
            'http://google.it',
            'https://google.de',
            'https://google.com',
            'https://google.it',
            'http://www.google.de',
            'http://www.google.com',
            'http://www.google.it',
        ];

        $memoryStart = memory_get_usage(true);

        foreach ($domains as $key => $domain) {
            $purl[$key] = Url::parse($domain);
        }

        $memoryEnd = memory_get_usage(true);

        self::assertEquals($this->roundMemoryUsage($memoryStart), $this->roundMemoryUsage($memoryEnd));
    }

    private function roundMemoryUsage(float $size) : float
    {
        return round($size / pow(1024, $i = floor(log($size, 1024))));
    }
}

<?php declare(strict_types=1);

namespace SHMCache\Test;

use PHPUnit\Framework\TestCase;
use SHMCache\Cache;

class CacheTest extends TestCase
{
    public function test_save_get()
    {
        $key1 = 'say';
        $data1 = 'hello world';

        try {
            self::assertFalse(Cache::saveCache('', $data1));
        } catch (\ErrorException $err) {
            self::assertNotEmpty($err);
        }

        self::assertTrue(Cache::saveCache($key1, $data1));
        self::assertEquals(Cache::getCache($key1), $data1);

        $key2 = 'try';
        $data2 = 'catch';

        self::assertTrue(Cache::saveCache($key2, $data2));
        self::assertEquals(Cache::getCache($key1), $data1);
        self::assertEquals(Cache::getCache($key2), $data2);
    }
}

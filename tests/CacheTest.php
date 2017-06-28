<?php declare(strict_types=1);

namespace SHMCache\Test;

use PHPUnit\Framework\TestCase;
use SHMCache\Cache;

class CacheTest extends TestCase
{
    public function test_save_get()
    {
        Cache::cleanCache();

        try {
            self::assertFalse(Cache::saveCache('', 'hello world'));
        } catch (\ErrorException $err) {
            self::assertNotEmpty($err);
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertTrue(Cache::saveCache("say$i", "hello world$i"));
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals(Cache::getCache("say$i"), "hello world$i");
        }
    }

    public function test_clean()
    {
        Cache::cleanCache();

        for ($i = 0; $i < 1000; $i++) {
            self::assertFalse(Cache::getCache("say$i"));
        }
    }

    public function test_timeout()
    {
        Cache::cleanCache();

        for ($i = 0; $i < 1000; $i++) {
            self::assertTrue(Cache::saveCache("say$i", "hello world$i", 1));
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals(Cache::getCache("say$i"), "hello world$i");
        }

        sleep(1); // sleep 1 second for waiting timeout

        for ($i = 0; $i < 1000; $i++) {
            self::assertFalse(Cache::getCache("say$i"));
        }
    }
}

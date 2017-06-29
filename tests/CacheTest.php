<?php declare(strict_types=1);

namespace SHMCache\Test;

use PHPUnit\Framework\TestCase;
use SHMCache\Cache;

/**
 * Class CacheTest
 * @package SHMCache\Test
 */
class CacheTest extends TestCase
{
    /**
     * Base Cache testing
     */
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

    /**
     * @depends test_save_get
     */
    public function test_clean()
    {
        Cache::cleanCache();

        for ($i = 0; $i < 1000; $i++) {
            self::assertFalse(Cache::getCache("say$i"));
        }
    }

    /**
     * @depends test_clean
     */
    public function test_timeout()
    {
        for ($i = 0; $i < 1000; $i++) {
            self::assertTrue(Cache::saveCache("say$i", "hello world$i", 1)); // 1 second timeout
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

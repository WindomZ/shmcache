<?php declare(strict_types=1);

namespace SHMCache\Test;

use PHPUnit\Framework\TestCase;
use SHMCache\Block;

class BlockTest extends TestCase
{
    public function test_save_get()
    {
        $cache = new Block();
        try {
            self::assertFalse($cache->save('', 'hello world'));
        } catch (\ErrorException $err) {
            self::assertNotEmpty($err);
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertTrue($cache->save("say$i", "hello world$i"));
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals($cache->get("say$i"), "hello world$i");
        }
    }

    public function test_clean()
    {
        $cache = new Block();
        $cache->clean();

        for ($i = 0; $i < 1000; $i++) {
            self::assertFalse($cache->get("say$i"));
        }
    }

    public function test_timeout_1()
    {
        $cache = new Block(1);
        $cache->clean();

        for ($i = 0; $i < 1000; $i++) {
            self::assertTrue($cache->save("say$i", "hello world$i"));
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals($cache->get("say$i"), "hello world$i");
        }

        sleep(1); // sleep 1 second for waiting timeout

        for ($i = 0; $i < 1000; $i++) {
            self::assertFalse($cache->get("say$i"));
        }
    }

    public function test_timeout_2()
    {
        $cache = new Block();
        $cache->clean();

        for ($i = 0; $i < 1000; $i++) {
            self::assertTrue($cache->save("say$i", "hello world$i", 1));
        }

        for ($i = 0; $i < 1000; $i++) {
            self::assertEquals($cache->get("say$i"), "hello world$i");
        }

        sleep(1); // sleep 1 second for waiting timeout

        for ($i = 0; $i < 1000; $i++) {
            self::assertFalse($cache->get("say$i"));
        }
    }
}

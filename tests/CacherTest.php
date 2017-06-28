<?php declare(strict_types=1);

namespace SHMCache\Test;

use PHPUnit\Framework\TestCase;
use SHMCache\Cacher;

class CacherTest extends TestCase
{
    public function test_save_get()
    {
        $key1 = 'say';
        $data1 = 'hello world';

        $cache = new Cacher();
        try {
            self::assertFalse($cache->save('', $data1));
        } catch (\ErrorException $err) {
            self::assertNotEmpty($err);
        }

        self::assertTrue($cache->save($key1, $data1));
        self::assertEquals($cache->get($key1), $data1);

        $key2 = 'try';
        $data2 = 'catch';

        self::assertTrue($cache->save($key2, $data2));
        self::assertEquals($cache->get($key1), $data1);
        self::assertEquals($cache->get($key2), $data2);
    }

    public function test_timeout()
    {
        $key1 = 'say';
        $data1 = 'hello world';

        $cache = new Cacher(1);
        try {
            self::assertFalse($cache->save('', $data1));
        } catch (\ErrorException $err) {
            self::assertNotEmpty($err);
        }

        self::assertTrue($cache->save($key1, $data1));
        self::assertEquals($cache->get($key1), $data1);

        $key2 = 'try';
        $data2 = 'catch';

        self::assertTrue($cache->save($key2, $data2));
        self::assertEquals($cache->get($key1), $data1);
        self::assertEquals($cache->get($key2), $data2);

        sleep(1);

        self::assertFalse($cache->get($key1));
        self::assertFalse($cache->get($key2));
    }
}

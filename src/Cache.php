<?php declare(strict_types=1);

namespace SHMCache;

/**
 * A lightweight, out-of-the-box shared memory operation
 * Class Cache
 * @package SHMCache
 */
class Cache extends Block
{
    /**
     * @var Cache
     */
    private static $_instance;

    private function __clone()
    {
    }

    /**
     * Single instance
     * @return Cache
     */
    private static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new Cache();
        }

        return self::$_instance;
    }

    /**
     * Cache constructor.
     * @param int $timeout [optional] seconds
     */
    public function __construct(int $timeout = 0)
    {
        parent::__construct($timeout);
    }

    /**
     * Save $value by $key to cache
     * @param string $key
     * @param mixed $value
     * @param int $seconds [optional]
     * @return bool
     */
    public static function saveCache(string $key, $value, int $seconds = 0): bool
    {
        return self::getInstance()->save($key, $value, $seconds);
    }

    /**
     * Get the $value by $key from cache
     * @param string $key
     * @return bool|mixed
     */
    public static function getCache(string $key)
    {
        return self::getInstance()->get($key);
    }

    /**
     * Clean all cache data
     */
    public static function cleanCache()
    {
        self::getInstance()->clean();
    }
}

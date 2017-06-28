<?php declare(strict_types=1);

namespace SHMCache;

/**
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
     * @param int [$timeout]
     */
    public function __construct($timeout = 0)
    {
        parent::__construct($timeout);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws \ErrorException
     */
    public static function saveCache($key, $value)
    {
        return self::getInstance()->save($key, $value);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public static function getCache($key)
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

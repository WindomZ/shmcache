<?php declare(strict_types=1);

namespace SHMCache;

/**
 * Shared Memory Block
 * Class Block
 * @package SHMCache
 */
class Block extends shmop
{
    /**
     * @var int
     */
    protected $timeout = 0;

    /**
     * Get timeout var
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Block constructor.
     * @param int $timeout [optional]
     * @param int $id [optional]
     */
    public function __construct($timeout = 0, $id = 0)
    {
        $this->timeout = $timeout;
        parent::__construct($id > 0 ? $id : 0);
    }

    /**
     * Save $value by $key to cache
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws \ErrorException
     */
    public function save($key, $value)
    {
        if (empty($key)) {
            throw  new \ErrorException('"key" should not be empty!');
        }

        $data = $this->read();
        if (!is_array($data)) {
            $data = array();
        }

        $data[$key] = $value;

        return parent::write($data, $this->timeout);
    }

    /**
     * Get the $value by $key from cache
     * @param string $key
     * @return bool|mixed
     */
    public function get($key)
    {
        if (empty($key)) {
            return false;
        }

        $value = $this->read();
        if (!is_array($value) || !isset($value[$key])) {
            return false;
        }

        return $value[$key];
    }

    /**
     * Clean all cache data
     */
    public function clean()
    {
        parent::clean();
    }
}

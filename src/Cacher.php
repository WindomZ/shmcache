<?php declare(strict_types=1);

namespace SHMCache;

/**
 * Class Cacher
 * @package SHMCache
 */
class Cacher extends shmop
{
    /**
     * @var int
     */
    protected $timeout = 0;

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Cacher constructor.
     * @param int [$timeout]
     * @param int [$id]
     */
    public function __construct(int $timeout = 0, int $id = 0)
    {
        $this->timeout = $timeout;
        parent::__construct($id > 0 ? $id : 0);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     * @throws \ErrorException
     */
    public function save(string $key, $value): bool
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
     * @param string $key
     * @return bool|mixed
     */
    public function get(string $key)
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

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
     * @param mixed $data
     * @return bool
     * @throws \ErrorException
     */
    public function save(string $key, $data): bool
    {
        if (empty($key)) {
            throw  new \ErrorException('"key" should not be empty!');
        }

        $value = $this->read();
        if (!is_array($value)) {
            $value = array();
        }

        $value[$key] = $data;

        return parent::write($value, $this->timeout);
    }

    /**
     * @param string $key
     * @return bool|mixed
     * @throws \ErrorException
     */
    public function get(string $key)
    {
        if (empty($key)) {
            throw new \ErrorException('"key" should not be empty!');
        }

        $value = $this->read();
        if (!is_array($value) || !isset($value[$key])) {
            return false;
        }

        return $value[$key];
    }
}

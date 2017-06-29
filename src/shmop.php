<?php declare(strict_types=1);

namespace SHMCache;

/**
 * Abstract and scalable shared memory operation
 * Class shmop
 * @package SHMCache
 */
abstract class shmop
{
    /**
     * System's id for the shared memory block.
     * @var int
     */
    protected $id;

    /**
     * The permissions that you wish to assign to your memory segment, those
     * are the same as permission for a file. Permissions need to be passed
     * in octal form, like for example 0644
     * @var int
     */
    protected $mode = 0644;

    /**
     * Get system's id for the shared memory block.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the permissions that you wish to assign to your memory segment
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * shmop constructor.
     * @param int $id [optional]
     * @param int $mode [optional]
     */
    public function __construct($id = 0, $mode = 0)
    {
        if (empty($id)) {
            $id = ftok(__FILE__, "b");
        }
        if ($mode > 0) {
            $this->mode = $mode;
        }
        $this->id = $id;
    }

    /**
     * Whether there is a system's id
     * @param int $id [optional]
     * @return bool
     */
    protected function exists($id = 0)
    {
        return (bool)@shmop_open($id ? $id : $this->id, 'a', 0, 0);
    }

    /**
     * Get the microsecond from microtime() and offset $microseconds
     * @param int $microseconds [optional]
     * @return int
     */
    public function microtime(int $microseconds = 0): int
    {
        return intval(round(microtime(true) * 1000)) + $microseconds;
    }

    /**
     * Hook to package the mixed data
     * @param mixed $data
     * @return mixed
     */
    abstract protected function toPack($data);

    /**
     * Package to an array and serialize to a string
     * @param mixed $data
     * @param int $timeout [optional] seconds
     * @return string
     */
    protected function pack($data, int $timeout = 0)
    {
        return serialize(
            array(
                'data' => $this->toPack($data),
                'timeout' => $timeout ? $this->microtime($timeout * 1000) : 0,
            )
        );
    }

    /**
     * Hook to unpacking the mixed data
     * @param mixed $data
     * @return mixed
     */
    abstract protected function toUnpack($data);

    /**
     * Unpacking a string and parse no timeout data from array
     * @param string $data
     * @return mixed|bool
     */
    protected function unpack($data)
    {
        if ($data) {
            $data = unserialize($data);
            if (is_array($data) && isset($data['data']) && isset($data['timeout'])) {
                $timeout = intval($data['timeout']);
                if (!$timeout || $timeout >= $this->microtime()) {
                    return $this->toUnpack($data['data']);
                }
            }
        }

        return false;
    }

    /**
     * Write data into shared memory block
     * @param mixed $data
     * @param int $timeout [optional] seconds
     * @return bool
     */
    protected function write($data, $timeout = 0)
    {
        if (!$data) {
            return false;
        }

        $this->clean();

        $data = $this->pack($data, $timeout);

        $id = shmop_open($this->id, "n", $this->mode, strlen($data));
        if (!$id) {
            return false;
        }

        $size = shmop_write($id, $data, 0);

        return !empty($size);
    }

    /**
     * Read data from shared memory block
     * @return bool|mixed
     */
    protected function read()
    {
        if ($this->exists()) {
            $id = shmop_open($this->id, "a", 0, 0);
            if (!$id) {
                return false;
            }

            $data = shmop_read($id, 0, shmop_size($id));
            if (!$data) {
                return false;
            }

            $data = $this->unpack($data);
            shmop_close($id);

            return $data;
        }

        return false;
    }

    /**
     * Clean data from shared memory block
     */
    protected function clean()
    {
        if ($this->exists()) {
            $id = shmop_open($this->id, "a", 0, 0);
            shmop_delete($id);
            shmop_close($id);
        }
    }
}

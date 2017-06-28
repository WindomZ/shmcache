<?php declare(strict_types=1);

namespace SHMCache;

/**
 * Class shmop
 * @package SHMCache
 */
abstract class shmop
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $mode = 0644;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode(int $mode)
    {
        $this->mode = $mode;
    }

    /**
     * shmop constructor.
     * @param int [$id]
     */
    public function __construct(int $id = 0)
    {
        if (empty($id)) {
            $id = ftok(__FILE__, "b");
        }
        $this->id = $id;
    }

    /**
     * @param int [$id]
     * @return bool
     */
    protected function exists(int $id = 0): bool
    {
        return (bool)@shmop_open($id ? $id : $this->id, 'a', 0, 0);
    }

    /**
     * @param mixed $data
     * @param int $timeout
     * @return string
     */
    protected function pack($data, int $timeout = 0)
    {
        return serialize(
            array(
                'data' => $data,
                'timeout' => $timeout > 0 ? time() + $timeout : 0,
            )
        );
    }

    /**
     * @param string $data
     * @return mixed|bool
     */
    protected function unpack(string $data)
    {
        if ($data) {
            $data = unserialize($data);
            if (is_array($data) && isset($data['timeout'])) {
                $timeout = intval($data['timeout']);
                if (isset($data['data']) && ($timeout === 0 || time() < $timeout)) {
                    return $data['data'];
                }
            }
        }

        return false;
    }

    /**
     * @param mixed $data
     * @param int [$timeout]
     * @return bool
     */
    protected function write($data, int $timeout = 0): bool
    {
        if (!$data) {
            return false;
        }

        $this->clean();

        $data = $this->pack($data, $timeout);

        $id = shmop_open($this->id, "c", $this->mode, strlen($data));
        if (!$id) {
            return false;
        }

        return !empty(shmop_write($id, $data, 0));
    }

    /**
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
     * Clean shmop data
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

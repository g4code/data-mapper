<?php

namespace G4\DataMapper\Common;

class RawData implements \Countable
{

    /**
     * @var int
     */
    private $count;

    /**
     * @var array
     */
    private $data;

    /**
     * @var int
     */
    private $total;

    /**
     * @param array $data
     * @param int $total
     */
    public function __construct(array $data, $total)
    {
        $this->data = $data;
        $this->total = $total;
    }

    public function count()
    {
        if ($this->count === null) {
            $this->count = count($this->data);
        }
        return $this->count;
    }

    public function getAll()
    {
        return $this->data;
    }

    public function getOne()
    {
        return current($this->data);
    }

    public function getTotal()
    {
        return $this->total;
    }
}
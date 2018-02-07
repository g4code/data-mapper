<?php

namespace G4\DataMapper\Common;

class RawData implements \Countable
{

    const ID_IDENTIFIER = 'id';

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
        return $this->count() > 0
            ? current($this->data)
            : null;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getAllDataWithIdIdentifier()
    {
        $data = [];

        foreach($this->data as $item) {
            isset($item[self::ID_IDENTIFIER])
                ? $data[$item[self::ID_IDENTIFIER]] = $item
                : $data []= $item;
        }

        return $data;
    }
}

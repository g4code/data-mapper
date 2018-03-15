<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Exception\EmptyDataException;

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
        return $this->count() > 0
            ? current($this->data)
            : null;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getAllDataWithIdIdentifier($identifier)
    {
        $rawData = ($this->data === null || empty($this->data)) ? [] : $this->data;

        $data = [];

        foreach($rawData as $item) {
            if(isset($item[$identifier])) {
                $data[$item[$identifier]] = $item;
            }
        }

        if (empty($data)) {
           throw new EmptyDataException();
        }

        return $data;
    }
}

<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Exception\EmptyDataException;

class SimpleRawData implements \Countable
{
    /**
     * @var array
     */
    private $data;

    private $count;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
}

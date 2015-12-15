<?php

namespace G4\DataMapper\Common;

class RawData
{

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

    public function getData()
    {
        return $this->data;
    }

    public function getTotal()
    {
        return $this->total;
    }
}
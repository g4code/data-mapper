<?php

namespace G4\DataMapper\Domain;

class TotalCount
{
    private $total;
    private $value;

    public function __construct($hits)
    {
        $this->setTotal($hits);
        $this->setValue();
    }

    public function getValue()
    {
        return $this->value;
    }

    private function setTotal($hits)
    {
        if (is_array($hits) && array_key_exists('total', $hits)) {
            $this->total = $hits['total'];
        } else {
            $this->total = $hits;
        }
    }

    private function setValue()
    {
        switch (true) {
            case is_array($this->total) && array_key_exists('value', $this->total):
                $this->value = $this->total['value'];
                break;
            case is_numeric($this->total) || is_string($this->total):
                $this->value = (int) $this->total;
                break;
            default:
                $this->value = 0;
                break;
        }
    }
}

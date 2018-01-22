<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\RangeValue;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\ValueInterface;
use G4\DataMapper\Exception\NotAnInstanceException;

class Quote
{
    const CONNECTOR_AND = ' AND ';

    /**
     * @var mixed
     */
    private $value;


    public function __construct(ValueInterface $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        if ($this->value instanceof SingleValue) {
            if (is_int($this->value->getValue())) {
                $value = $this->formatInteger();
            } elseif (is_float($this->value->getValue())) {
                $value = $this->formatFloat();
            } elseif (is_array($this->value->getValue())) {
                $value = $this->formatArray();
            } else {
                $value = $this->formatString();
            }
        } else {
            $value = $this->formatRangeValue();
        }

        return $value;
    }

    private function formatInteger()
    {
        return (string) $this->value;
    }

    private function formatFloat()
    {
        return sprintf('%F', $this->value->getValue());
    }

    private function formatArray()
    {
        foreach ($this->value->getValue() as $key => $value) {
            $formattedArray[$key] = "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
        }
        return "(" . join(", ", $formattedArray) . ")";
    }

    private function formatString()
    {
        return "'" . addcslashes($this->value->getValue(), "\000\n\r\\'\"\032") . "'";
    }

    private function formatRangeValue()
    {
        return $this->value->getMin() . self::CONNECTOR_AND . $this->value->getMax();
    }
}

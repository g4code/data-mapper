<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\SingleValue;

class Quote
{
    /**
     * @var SingleValue
     */
    private $singleValue;


    public function __construct(SingleValue $singleValue)
    {
        $this->singleValue = $singleValue;
    }

    public function __toString()
    {
        if ($this->singleValue->isInteger()) {
            $value = $this->formatInteger();
        } elseif ($this->singleValue->isFloat()) {
            $value = $this->formatFloat();
        } elseif ($this->singleValue->isArray()) {
            $value = $this->formatArray();
        } else {
            $value = $this->formatString();
        }

        return $value;
    }

    private function formatInteger()
    {
        return (string) $this->singleValue;
    }

    private function formatFloat()
    {
        return sprintf('%F', $this->singleValue->getValue());
    }

    private function formatArray()
    {
        foreach ($this->singleValue->getValue() as $key => $value) {
            $formattedArray[$key] = "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
        }
        return "(" . join(", ", $formattedArray) . ")";
    }

    private function formatString()
    {
        return "'" . addcslashes($this->singleValue->getValue(), "\000\n\r\\'\"\032") . "'";
    }
}

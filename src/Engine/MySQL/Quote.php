<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\ComparisonValue;

class Quote
{

    private $comparisonValue;


    public function __construct(ComparisonValue $comparisonValue)
    {
        $this->comparisonValue = $comparisonValue;
    }

    public function __toString()
    {
        if ($this->comparisonValue->isInteger()) {
            $value = $this->formatInteger();
        } elseif ($this->comparisonValue->isFloat()) {
            $value = $this->formatFloat();
        } elseif ($this->comparisonValue->isArray()) {
            $value = $this->formatArray();
        } else {
            $value = $this->formatString();
        }

        return $value;
    }

    private function formatInteger()
    {
        return (string) $this->comparisonValue;
    }

    private function formatFloat()
    {
        return sprintf('%F', $this->comparisonValue->getValue());
    }

    private function formatArray()
    {
        foreach ($this->comparisonValue->getValue() as $key => $value) {
            $formattedArray[$key] = "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
        }
        return "(" . join(", ", $formattedArray) . ")";
    }

    private function formatString()
    {
        return "'" . addcslashes($this->comparisonValue->getValue(), "\000\n\r\\'\"\032") . "'";
    }
}

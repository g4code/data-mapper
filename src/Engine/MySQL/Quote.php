<?php

namespace G4\DataMapper\Engine\MySQL;

class Quote
{

    private $value;


    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        if (is_int($this->value)) {
            $value = $this->formatInteger();
        }
        elseif(is_float($this->value)) {
            $value = $this->formatFloat();
        }
        elseif (is_array($this->value)) {
            $value = $this->formatArray();
        } else {
            $value = "'" . addcslashes($this->value, "\000\n\r\\'\"\032") . "'";
        }

        return $value;
    }

    private function formatInteger()
    {
        return (string) $this->value;
    }

    private function formatFloat()
    {
        return sprintf('%F', $this->value);
    }

    private function formatArray()
    {
        foreach($this->value as $key => $value) {
            $this->value[$key] = "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
        }
        return "(" . join(", ", $this->value) . ")";
    }
}
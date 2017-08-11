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
            return (string) $this->value;
        }
        if (is_float($this->value)) {
            return sprintf('%F', $this->value);
        }
        if (is_array($this->value)) {
            foreach($this->value as $key => $value) {
                $this->value[$key] = "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
            }
            return "(" . join(", ", $this->value) . ")";
        }
        return "'" . addcslashes($this->value, "\000\n\r\\'\"\032") . "'";
    }
}
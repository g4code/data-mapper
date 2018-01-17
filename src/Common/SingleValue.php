<?php

namespace G4\DataMapper\Common;

class SingleValue
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isInteger()
    {
        return is_int($this->value);
    }

    public function isFloat()
    {
        return is_float($this->value);
    }

    public function isString()
    {
        return is_string($this->value);
    }

    public function isArray()
    {
        return is_array($this->value);
    }
}

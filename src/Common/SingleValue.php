<?php

namespace G4\DataMapper\Common;

class SingleValue implements ValueInterface
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
        return is_array($this->value) ? join(',', $this->value) : (string) $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isNull()
    {
        return $this->value === null;
    }
}

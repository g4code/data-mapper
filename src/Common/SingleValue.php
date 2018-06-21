<?php

namespace G4\DataMapper\Common;

class SingleValue implements ValueInterface
{
    const EMPTY_ARRAY = [];
    const NULL_VALUE  = null;

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

    public function isEmpty()
    {
        return in_array($this->value, [self::NULL_VALUE, self::EMPTY_ARRAY], true);
    }
}

<?php

namespace G4\DataMapper\Engine\Http;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\ValueInterface;

class HttpComparisonFormatter implements ComparisonFormatterInterface
{

    public function format($name, Operator $operator, ValueInterface $value)
    {
        return sprintf("%s=%s", (string) $name, (string) $value);
    }
}
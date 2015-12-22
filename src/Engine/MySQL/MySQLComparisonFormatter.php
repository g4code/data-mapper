<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Engine\MySQL\Quote;

class MySQLComparisonFormatter implements ComparisonFormatterInterface
{

    public function format($name, $operator, $value)
    {
        return sprintf("%s %s %s",
                $name,
                $operator,
                (string) (new Quote($value))
        );
    }
}
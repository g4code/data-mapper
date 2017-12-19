<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\ComparisonFormatterInterface;
use G4\DataMapper\Engine\MySQL\Quote;
use G4\DataMapper\Common\Selection\Operator;

class MySQLComparisonFormatter implements ComparisonFormatterInterface
{

    private $map = [
        Operator::EQUAL                => '=',
        Operator::GRATER_THAN          => '>',
        Operator::GRATER_THAN_OR_EQUAL => '>=',
        Operator::IN                   => 'IN',
        Operator::LESS_THAN            => '<',
        Operator::LESS_THAN_OR_EQUAL   => '<=',
        Operator::LIKE                 => 'LIKE',
        Operator::NOT_EQUAL            => '<>',
        Operator::NOT_IN               => 'NOT IN',
    ];

    public function format($name, Operator $operator, $value)
    {
        return sprintf(
            "%s %s %s",
            $name,
            $this->operatorMap($operator),
            $this->quote($value)
        );
    }

    private function quote($value)
    {
        return (string) new Quote($value);
    }

    private function operatorMap(Operator $operator)
    {
        $symbol = $operator->getSymbol();

        if (!isset($this->map[$symbol])) {
            throw new \Exception('Operator not in map', 101);
        }

        return $this->map[$symbol];
    }
}

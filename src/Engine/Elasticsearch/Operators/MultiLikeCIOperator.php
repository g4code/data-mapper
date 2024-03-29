<?php

namespace G4\DataMapper\Engine\Elasticsearch\Operators;

use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\QueryConnector;
use G4\DataMapper\Common\SingleValue;

/**
 * Class MultiLikeCIOperator
 *
 * @package G4\DataMapper\Engine\Elasticsearch\Operators
 */
class MultiLikeCIOperator extends LikeCIOperator implements QueryOperatorInterface
{

    public function __construct($name, SingleValue $value)
    {
        parent::__construct($name, $this->formatValue($value));
    }

    public function format()
    {
        return parent::format(); // TODO: Change the autogenerated stub
    }

    private function formatValue(SingleValue $value)
    {
        return new SingleValue(str_replace([" ","-"], "* AND *", $value));
    }
}

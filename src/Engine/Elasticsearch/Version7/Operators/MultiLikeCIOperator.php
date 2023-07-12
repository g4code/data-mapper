<?php

namespace G4\DataMapper\Engine\Elasticsearch\Version7\Operators;

use G4\DataMapper\Common\QueryOperatorInterface;
use G4\DataMapper\Common\SingleValue;

/**
 * Class MultipleLikeCIOperator
 * dummy class to prevent multiplelikeoperator falling to default/version 2.4 es operator
 * @package G4\DataMapper\Engine\Elasticsearch\Operators
 */
class MultiLikeCIOperator extends LikeCIOperator implements QueryOperatorInterface
{
    public function __construct($name, SingleValue $value)
    {
        parent::__construct($name, $value);
    }

    public function format()
    {
        return parent::format();
    }
}

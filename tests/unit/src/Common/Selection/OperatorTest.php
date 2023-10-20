<?php

use G4\DataMapper\Common\Selection\Operator;

class OperatorTest extends \PHPUnit\Framework\TestCase
{

    public function testGetSymbol()
    {
        $operator = new Operator(Operator::LESS_THAN);

        $this->assertEquals(Operator::LESS_THAN, $operator->getSymbol());
    }

    public function testNotValidSymbol()
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage('Symbol is not valid');

        $operator = new Operator('not_valid');
    }
}
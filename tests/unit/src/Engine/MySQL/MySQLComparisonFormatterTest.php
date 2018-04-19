<?php

use G4\DataMapper\Engine\MySQL\MySQLComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\RangeValue;

class MySQLComparisonFormatterTest extends PHPUnit_Framework_TestCase
{

    private $comparisonFormatter;

    private $operatorMock;


    protected function setUp()
    {
        $this->comparisonFormatter = new MySQLComparisonFormatter();

        $this->operatorMock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Operator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        $this->comparisonFormatter = null;
        $this->operatorMock        = null;
    }


    public function testEqual()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::EQUAL);

        $this->assertEquals('name = \'test\'', $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('test')));
    }

    public function testGreaterThan()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::GRATER_THAN);

        $this->assertEquals('age > 18', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue(18)));
    }

    public function testGreaterThanOrEqual()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::GRATER_THAN_OR_EQUAL);

        $this->assertEquals('age >= 18', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue(18)));
    }

    public function testLessThan()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::LESS_THAN);

        $this->assertEquals('age < 18', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue(18)));
    }

    public function testLessThanOrEqual()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::LESS_THAN_OR_EQUAL);

        $this->assertEquals('age <= 18', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue(18)));
    }

    public function testLike()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::LIKE);

        $this->assertEquals('name LIKE \'test\'', $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('test')));
    }

    public function testIn()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::IN);

        $this->assertEquals("age IN ('1', '2', '3', '4')", $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue([1, 2, 3, 4])));
    }

    public function testNotEqual()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::NOT_EQUAL);

        $this->assertEquals('name <> \'test\'', $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('test')));
    }

    public function testNotIn()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::NOT_IN);

        $this->assertEquals("age NOT IN ('1', '2', '3', '4')", $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue([1, 2, 3, 4])));
    }

    public function testBetween()
    {
        $this->operatorMock->expects($this->once())->method('getSymbol')->willReturn(Operator::BETWEEN);

        $this->assertEquals('age BETWEEN 1 AND 18', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(1, 18)));
    }

    public function testOperatorNotInMap()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn('not_in_map');

        $this->expectException(\G4\DataMapper\Exception\InvalidValueException::class);
        $this->expectExceptionCode(14010);
        $this->expectExceptionMessage('Operator not in map');
        $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('test'));
    }
}

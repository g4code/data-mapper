<?php

use G4\DataMapper\Engine\MySQL\MySQLComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;

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

    public function testOperatorNotInMap()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn('not_in_map');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Operator not in map');
        $this->comparisonFormatter->format('name', $this->operatorMock, 'test');
    }
}

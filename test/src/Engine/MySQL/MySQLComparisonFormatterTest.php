<?php

use G4\DataMapper\Engine\MySQL\MySQLComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;

class MySQLComparisonFormatterTest extends PHPUnit_Framework_TestCase
{

    private $comparisonFormatter;

    private $operatorMock;


    protected function setUp()
    {
        $this->comparisonFormatter = new MySQLComparisonFormatter();

        $this->operatorMock = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Operator')
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

        $this->assertEquals('name = \'test\'', $this->comparisonFormatter->format('name', $this->operatorMock, 'test'));
    }

    public function testOperatorNotInMap()
    {
        $this->operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn('not_in_map');

        $this->setExpectedException('\Exception', 'Operator not im map');
        $this->comparisonFormatter->format('name', $this->operatorMock, 'test');
    }
}
<?php

use G4\DataMapper\Engine\MySQL\MySQLComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;

class MySQLComparisonFormatterTest extends PHPUnit_Framework_TestCase
{

    private $comparisonFormatter;

    protected function setUp()
    {
        $this->comparisonFormatter = new MySQLComparisonFormatter();
    }

    protected function tearDown()
    {
        $this->comparisonFormatter = null;
    }


    public function testEqual()
    {
        $operatorMock = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Operator')
            ->disableOriginalConstructor()
            ->getMock();

        $operatorMock->expects($this->once())
            ->method('getSymbol')
            ->willReturn(Operator::EQUAL);

        $this->assertEquals('name = \'test\'', $this->comparisonFormatter->format('name', $operatorMock, 'test'));
    }
}
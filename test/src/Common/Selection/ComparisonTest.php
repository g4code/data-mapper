<?php

use G4\DataMapper\Common\Selection\Comparison;

class ComparisonTest extends PHPUnit_Framework_TestCase
{

    public function testGetComparison()
    {
        $operatorStub = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Operator')
            ->disableOriginalConstructor()
            ->getMock();

        $comparison = new Comparison('name', $operatorStub, 'test');

        $comparisonFormatterMock = $this->getMockBuilder('\G4\DataMapper\Common\ComparisonFormatterInterface')
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $comparisonFormatterMock->expects($this->once())
            ->method('format')
            ->willReturn('name = test');

        $this->assertEquals('name = test', $comparison->getComparison($comparisonFormatterMock));
    }
}
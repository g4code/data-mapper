<?php

use G4\DataMapper\Common\Selection\Comparison;

class ComparisonTest extends PHPUnit_Framework_TestCase
{

    public function testGetComparison()
    {
        $comparison = new Comparison('name', 'equal', 'test');

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
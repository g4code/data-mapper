<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;

class ElasticsearchComparisonFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchComparisonFormatter
     */
    private $comparisonFormatter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $operatorMock;

    protected function setUp()
    {
        $this->comparisonFormatter = new ElasticsearchComparisonFormatter();

        $this->operatorMock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Operator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown()
    {
        $this->comparisonFormatter = null;
        $this->operatorMock        = null;
    }

    public function testEqual()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::EQUAL);

        $this->assertEquals(['match' => ['age' => '15']], $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('15')));
    }

    public function testGreaterThan()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::GRATER_THAN);

        $this->assertEquals(['range' => ['age' => ['gt' => '18']]], $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testLessThan()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LESS_THAN);

        $this->assertEquals(['range' => ['age' => ['lt' => '18']]], $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testGreaterThanOrEqual()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::GRATER_THAN_OR_EQUAL);

        $this->assertEquals(['range' => ['age' => ['gte' => '18']]], $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testLessThanOrEqual()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LESS_THAN_OR_EQUAL);

        $this->assertEquals(['range' => ['age' => ['lte' => '18']]], $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testIn()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::IN);

        $this->assertEquals(['terms' => ['age' => [18, 19, 20]]], $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18, 19, 20')));
    }

    public function testBetween()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals(['range' => ['age' => ['gt' => 12, 'lt' => 20]]], $this->comparisonFormatter->format('age', $this->operatorMock, new \G4\DataMapper\Common\RangeValue(12, 20)));
    }
}

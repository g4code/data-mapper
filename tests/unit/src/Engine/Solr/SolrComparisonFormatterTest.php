<?php

use G4\DataMapper\Engine\Solr\SolrComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\RangeValue;

class SolrComparisonFormatterTest extends \PHPUnit\Framework\TestCase
{

    private $comparisonFormatter;

    private $operatorMock;

    protected function setUp(): void
    {
        $this->comparisonFormatter = new SolrComparisonFormatter();

        $this->operatorMock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Operator::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function tearDown(): void
    {
        $this->comparisonFormatter = null;
        $this->operatorMock        = null;
    }

    public function testEqual()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::EQUAL);

        $this->assertEquals('name:test', $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('test')));
    }

    public function testGreaterThan()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::GRATER_THAN);

        $this->assertEquals('age:{18 TO *}', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testLessThan()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LESS_THAN);

        $this->assertEquals('age:{* TO 18}', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testGreaterThanOrEqual()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::GRATER_THAN_OR_EQUAL);

        $this->assertEquals('age:[18 TO *]', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testLessThanOrEqual()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LESS_THAN_OR_EQUAL);

        $this->assertEquals('age:[* TO 18]', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue('18')));
    }

    public function testIn()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::IN);

        $this->assertEquals('age:(18 OR 19 OR 20)', $this->comparisonFormatter->format('age', $this->operatorMock, new SingleValue(' 18, 19  ,  20  ')));
    }

    public function testLike()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LIKE);

        $this->assertEquals('name:*Test*User*', $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('Test User')));
    }

    public function testBetween()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[1 TO 18]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(1, 18)));
    }

    public function testBetweenZeroNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[0 TO *]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(null, 0)));
    }

    public function testBetweenNullNumber()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[* TO 5]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(null, 5)));
    }

    public function testBetweenNumberNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[5 TO *]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(5, null)));
    }

    public function testBetweenNullZero()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[0 TO *]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(0, null)));
    }

    public function testBetweenNullNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[* TO *]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(null, null)));
    }

    public function testBetweenMinValueNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[* TO 18]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(null, 18)));
    }

    public function testBetweenMaxValueNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals('age:[1 TO *]', $this->comparisonFormatter->format('age', $this->operatorMock, new RangeValue(1, null)));
    }

    public function testTimeFromInMinutesPositiveValue()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::TIME_FROM_IN_MINUTES);

        $this->assertEquals('online:[NOW-30MINUTES TO *]', $this->comparisonFormatter->format('online', $this->operatorMock, new SingleValue('30')));
    }

    public function testTimeFromInMinutesNegativeValue()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::TIME_FROM_IN_MINUTES);

        $this->assertEquals('online:[NOW-30MINUTES TO *]', $this->comparisonFormatter->format('online', $this->operatorMock, new SingleValue('-30')));
    }
}

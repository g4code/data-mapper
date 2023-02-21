<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchComparisonFormatter;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchIdentity;

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
        $this->comparisonFormatter = new ElasticsearchComparisonFormatter(new ElasticsearchIdentity(2));

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

    public function testEqualCI()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::EQUAL_CI);

        $this->assertEquals(
            ['match' => [
                'email' => [
                    'query' => 'text@example.com',
                    'type' => 'phrase'
                ]
            ]],
            $this->comparisonFormatter->format('email', $this->operatorMock, new SingleValue('text@example.com'))
        );
    }

    public function testEqualCIEsVersion7()
    {
        $comparisonFormatter = new ElasticsearchComparisonFormatter(new ElasticsearchIdentity(7));

        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::EQUAL_CI);

        $this->assertEquals(
            ['match' => [
                'email' => [
                    'query' => 'text@example.com'
                ]
            ]],
            $comparisonFormatter->format('email', $this->operatorMock, new SingleValue('text@example.com'))
        );
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

    public function testBetweenMinValueNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals(['range' => ['age' => ['lt' => 20]]], $this->comparisonFormatter->format('age', $this->operatorMock, new \G4\DataMapper\Common\RangeValue(null, 20)));
    }

    public function testBetweenMaxValueNull()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::BETWEEN);

        $this->assertEquals(['range' => ['age' => ['gt' => 12]]], $this->comparisonFormatter->format('age', $this->operatorMock, new \G4\DataMapper\Common\RangeValue(12, null)));
    }

    public function testTimeFromInMinutes()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::TIME_FROM_IN_MINUTES);

        $this->assertEquals([
            'range' => [
                'online' => [
                    'gt'     => strtotime("-15 minute", time()),
                    'format' => 'epoch_second',
                ]
            ]
        ], $this->comparisonFormatter->format('online', $this->operatorMock, new SingleValue(15)));
    }

    public function testLike()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LIKE);

        $this->assertEquals(['wildcard' => ['name' => '*lada*']], $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('lada')));
    }

    public function testLikeCI()
    {
        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LIKE_CI);

        $this->assertEquals(['query_string' => ['query' => 'name:*lada*']], $this->comparisonFormatter->format('name', $this->operatorMock, new SingleValue('lada')));
    }

    public function testLikeCIVersion7()
    {
        $comparisonFormatter = new ElasticsearchComparisonFormatter(new ElasticsearchIdentity(7));

        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::LIKE_CI);

        $this->assertEquals(
            ['query_string' => ['query' => 'name:*lada*']],
            $comparisonFormatter->format('name', $this->operatorMock, new SingleValue('LADA'))
        );
    }

    public function testQueryString()
    {

        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::QUERY_STRING);

        $this->assertEquals(
            [
                'query' => [
                        'query_string' => [
                                'query' => 'username: *test* OR email: *test*',
                                'analyze_wildcard' => true,
                            ],
                    ]
            ],
            $this->comparisonFormatter
                ->format('', $this->operatorMock, new SingleValue('username: *test* OR email: *test*'))
        );
    }

    public function testQueryStringVersion7()
    {
        $comparisonFormatter = new ElasticsearchComparisonFormatter(new ElasticsearchIdentity(7));

        $this->operatorMock->expects($this->any())
            ->method('getSymbol')
            ->willReturn(Operator::QUERY_STRING);

        $this->assertEquals(
            [
                'query_string' => [
                    'query' => 'username: *test* OR email: *test*'
                ]
            ],
            $comparisonFormatter->format('', $this->operatorMock, new SingleValue('username: *test* OR email: *test*'))
        );
    }
}

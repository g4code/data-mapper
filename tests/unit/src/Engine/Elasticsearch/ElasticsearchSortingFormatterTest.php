<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchSortingFormatter;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Exception\OrderNotInMapException;
use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class ElasticsearchSortingFormatterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ElasticsearchSortingFormatter
     */
    private $sortingFormatter;

    protected function setUp(): void
    {
        $this->sortingFormatter = new ElasticsearchSortingFormatter();
    }

    protected function tearDown(): void
    {
        $this->sortingFormatter = null;
    }

    public function testAscendingFormat()
    {
        $this->assertEquals(['name' => ['order' => 'asc']], $this->sortingFormatter->format('name', Sort::ASCENDING));
    }

    public function testDescendingFormat()
    {
        $this->assertEquals(['name' => ['order' => 'desc']], $this->sortingFormatter->format('name', Sort::DESCENDING));
    }

    public function testOrderNotInMap()
    {
        $this->expectException(OrderNotInMapException::class);
        $this->expectExceptionMessage(ErrorMessage::ORDER_NOT_IN_MAP);
        $this->expectExceptionCode(ErrorCode::ORDER_NOT_IN_MAP);

        $this->sortingFormatter->format('name', 'test');
    }
}

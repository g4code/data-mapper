<?php

use G4\DataMapper\Engine\Solr\SolrSortingFormatter;
use G4\DataMapper\Common\Selection\Sort;
use G4\DataMapper\Exception\OrderNotInMapException;
use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class SolrSortingFormatterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SolrSortingFormatter
     */
    private $sortingFormatter;

    protected function setUp(): void
    {
        $this->sortingFormatter = new SolrSortingFormatter();
    }

    protected function tearDown(): void
    {
        $this->sortingFormatter = null;
    }

    public function testAscendingFormat()
    {
        $this->assertEquals('name asc', $this->sortingFormatter->format('name', Sort::ASCENDING));
    }

    public function testDescendingFormat()
    {
        $this->assertEquals('name desc', $this->sortingFormatter->format('name', Sort::DESCENDING));
    }

    public function testOrderNotInMap()
    {
        $this->expectException(OrderNotInMapException::class);
        $this->expectExceptionMessage(ErrorMessage::ORDER_NOT_IN_MAP);
        $this->expectExceptionCode(ErrorCode::ORDER_NOT_IN_MAP);
        
        $this->sortingFormatter->format('name', 'test');
    }
}





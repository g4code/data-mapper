<?php

use G4\DataMapper\Engine\MySQL\MySQLSortingFormatter;
use G4\DataMapper\Common\Selection\Sort;


class MySQLSortingFormatterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MySQLSortingFormatter
     */
    private $sortingFormatter;


    protected function setUp()
    {
        $this->sortingFormatter = new MySQLSortingFormatter();
    }

    protected function tearDown()
    {
        $this->sortingFormatter = null;
    }

    public function testFormat()
    {
        $this->assertEquals('name ASC', $this->sortingFormatter->format('name', Sort::ASCENDING));
        $this->assertEquals('name DESC', $this->sortingFormatter->format('name', Sort::DESCENDING));
    }

    public function testOrderNotInMap()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Order not in map');
        $this->sortingFormatter->format('name', 'test');
    }
}

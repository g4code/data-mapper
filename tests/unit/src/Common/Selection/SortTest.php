<?php

use G4\DataMapper\Common\Selection\Sort;

class SortTest extends \PHPUnit\Framework\TestCase
{

    public function testGetSort()
    {
        $sortFormatterMock = $this->getMockBuilder('\G4\DataMapper\Common\SortingFormatterInterface')
            ->disableOriginalConstructor()
            ->setMethods(['format'])
            ->getMock();

        $sortFormatterMock
            ->expects($this->exactly(2))
            ->method('format');

        $sort = new Sort('name', Sort::ASCENDING);
        $sort->getSort($sortFormatterMock);

        $sort = new Sort('name', Sort::DESCENDING);
        $sort->getSort($sortFormatterMock);
    }

    public function testOrderIsNotValid()
    {
        $this->expectException('\Exception');
        $this->expectExceptionMessage('Sort order is not valid');
        new Sort('name', 'not_valid_order');
    }
}
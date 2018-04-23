<?php

use G4\DataMapper\Engine\Solr\SolrSelectionFactory;
use G4\DataMapper\Common\Selection\Comparison;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Common\RangeValue;

class SolrSelectionFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SolrSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder(\G4\DataMapper\Engine\Solr\SolrIdentity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new SolrSelectionFactory($this->identityMock);
    }

    protected function tearDown()
    {
        $this->identityMock = null;

        $this->selectionFactory = null;
    }

    public function testFieldNames()
    {
        $this->identityMock
            ->expects($this->exactly(2))
            ->method('getFieldNames')
            ->willReturn(['name']);

        $this->assertEquals('name', $this->selectionFactory->fieldNames());
    }

    public function testFieldNamesAsMultidimensionalArray()
    {
        $this->identityMock
            ->expects($this->exactly(2))
            ->method('getFieldNames')
            ->willReturn([['user_id' => 'id'],'name', 'gender', ['dob' => 'birthday'], 'city_id']);

        $this->assertEquals('user_id:id,name,gender,dob:birthday,city_id', $this->selectionFactory->fieldNames());
    }

    public function testEmptyFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn([]);

        $this->assertEquals('*', $this->selectionFactory->fieldNames());
    }

    public function testGroup()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getGrouping')
            ->willReturn([]);

        $this->assertEquals([], $this->selectionFactory->group());
    }

    public function testLimit()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getLimit')
            ->willReturn(5);

        $this->assertEquals(5, $this->selectionFactory->limit());
    }

    public function testOffset()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getOffset')
            ->willReturn(8);

        $this->assertEquals(8, $this->selectionFactory->offset());
    }

    public function testSort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([
                $this->getMockForSort('id', 'desc'),
                $this->getMockForSort('name', 'asc'),
            ]);

        $this->assertEquals('id desc,name asc', $this->selectionFactory->sort());
    }

    public function testGeodistSort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willreturn( [$this->getMockForSort('geodist()', 'asc')]);

        $this->assertEquals('geodist() asc', $this->selectionFactory->sort());
    }

    public function testCombinedSortAndGeodistSort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([
                $this->getMockForSort('geodist()', 'asc'),
                $this->getMockForSort('id', 'desc'),
                $this->getMockForSort('name', 'asc'),
            ]);

        $this->assertEquals('geodist() asc,id desc,name asc', $this->selectionFactory->sort());
    }

    public function testEmptySort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn('');

        $this->assertEquals('', $this->selectionFactory->sort());
    }

    public function testWhere()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(false);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([
                new Comparison('id', new Operator(Operator::EQUAL), new SingleValue(1)),
                new Comparison('age', new Operator(Operator::EQUAL), new SingleValue(18)),
                new Comparison('gender', new Operator(Operator::EQUAL), new SingleValue(null)),
                new Comparison('status', new Operator(Operator::IN), new SingleValue([])),
                new Comparison('registration_date', new Operator(Operator::BETWEEN), new RangeValue(null, null)),
            ]);

        $this->assertEquals('id:1 AND age:18', $this->selectionFactory->where());
    }

    public function testWhereWithRawQuery()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(false);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([
                new Comparison('age', new Operator(Operator::EQUAL), new SingleValue(18)),
            ]);

        $this->identityMock
            ->expects($this->once())
            ->method('hasRawQuery')
            ->willReturn(true);

        $this->identityMock
            ->expects($this->once())
            ->method('getRawQuery')
            ->willReturn("((gender:'F' AND sexual_orientation:'STRAIGHT') OR (gender:'M' AND sexual_orientation:'STRAIGHT'))");

        $this->assertEquals("((gender:'F' AND sexual_orientation:'STRAIGHT') OR (gender:'M' AND sexual_orientation:'STRAIGHT')) AND age:18", $this->selectionFactory->where());
    }

    public function testWhereIfIdentityIsVoid()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(true);

        $this->assertEquals('*:*', $this->selectionFactory->where());
    }

    private function getMockForSort($column, $sortDirection)
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Sort::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSort'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getSort')
            ->willReturn($column .' '. $sortDirection);

        return $mock;
    }
}

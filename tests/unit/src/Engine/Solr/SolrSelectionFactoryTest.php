<?php

use G4\DataMapper\Engine\Solr\SolrSelectionFactory;

class SolrSelectionFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SolrSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder('\G4\DataMapper\Common\Identity')
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
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['name']);

        $this->assertEquals(['name'], $this->selectionFactory->fieldNames());
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

        $this->assertEquals([
            ['id'   => 'desc'],
            ['name' => 'asc'],
        ], $this->selectionFactory->sort());
    }

    public function testEmptySort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([]);

        $this->assertEquals([], $this->selectionFactory->sort());
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
                $this->getMockForComparison('id', 1),
                $this->getMockForComparison('age', 18)
            ]);

        $this->assertEquals('id:1 AND age:18', $this->selectionFactory->where());
    }

    public function testWhereIfIdentityIsVoid()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(true);

        $this->assertEquals('1', $this->selectionFactory->where());
    }

    private function getMockForComparison($column, $value)
    {
        $mock = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Comparison')
            ->disableOriginalConstructor()
            ->setMethods(['getComparison'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->willReturn($column . ':' . $value);

        return $mock;
    }

    private function getMockForSort($column, $sortDirection)
    {
        $mock = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Sort')
            ->disableOriginalConstructor()
            ->setMethods(['getSort'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getSort')
            ->willReturn([$column => $sortDirection]);

        return $mock;
    }
}

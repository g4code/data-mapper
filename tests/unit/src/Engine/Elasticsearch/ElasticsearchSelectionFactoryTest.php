<?php

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchSelectionFactory;

class ElasticsearchSelectionFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ElasticsearchSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder(\G4\DataMapper\Common\Identity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new ElasticsearchSelectionFactory($this->identityMock);
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
            ->willReturn(['first_name', 'last_name', 'age']);

        $this->assertEquals(['first_name', 'last_name', 'age'], $this->selectionFactory->fieldNames());
    }

    public function testEmptyFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn([]);

        $this->assertEquals([], $this->selectionFactory->fieldNames());
    }

    public function testGroup()
    {
        $this->identityMock
            ->expects($this->any())
            ->method('getGrouping')
            ->willReturn('name');

        $this->assertEquals(['group_by_name' => [ 'terms' => ['field' => 'name']]], $this->selectionFactory->group());
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
            ['id'   => ['order' => 'desc']],
            ['name' => ['order' => 'asc']],
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
                $this->getMockForEqualComparison('id', 1),
                $this->getMockForEqualComparison('name', 'Test'),
                $this->getMockForGtComparison('age', 18),
            ]);

        $this->assertEquals(
            ['bool' =>
                ['must' =>
                    [
                        ['match' => ['id' => 1]],
                        ['match' => ['name' => 'Test']],
                        ['range' => ['age' => ['gt' => 18]]]
                    ]
                ]
            ], $this->selectionFactory->where());
    }

    public function testWhereIfIdentityIsVoid()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(true);

        $this->assertEquals(['bool' => ['must' => ['match_all' => []]]], $this->selectionFactory->where());
    }

    private function getMockForEqualComparison($column, $value)
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Comparison::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComparison'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->willReturn(['match' => [$column => $value]]);

        return $mock;
    }

    private function getMockForGtComparison($column, $value)
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Comparison::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComparison'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->willReturn(['range' => [$column => ['gt' => $value]]]);

        return $mock;
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
            ->willReturn([$column => ['order' => $sortDirection]]);

        return $mock;
    }
}

<?php

use G4\DataMapper\Engine\MySQL\MySQLSelectionFactory;
use G4\DataMapper\Common\Identity;
use G4\DataMapper\Common\Selection\Comparison;

class MySQLSelectionFactoryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var MySQLSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;


    protected function setUp(): void
    {
        $this->identityMock = $this->getMockBuilder(\G4\DataMapper\Common\Identity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new MySQLSelectionFactory($this->identityMock);
    }

    protected function tearDown(): void
    {
        $this->identityMock     = null;
        $this->selectionFactory = null;
    }

    public function testEmptyFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn([]);

        $this->assertEquals('*', $this->selectionFactory->fieldNames());
    }

    public function testInvalidTypeFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn('tralala');

        $this->assertEquals('*', $this->selectionFactory->fieldNames());
    }

    public function testFieldNames()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getFieldNames')
            ->willReturn(['name']);

        $this->assertEquals(['name'], $this->selectionFactory->fieldNames());
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
            ->willReturn(9);

        $this->assertEquals(9, $this->selectionFactory->limit());
    }

    public function testOffset()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getOffset')
            ->willReturn(2);

        $this->assertEquals(2, $this->selectionFactory->offset());
    }

    public function testSort()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('getSorting')
            ->willReturn([
                $this->getMockForSort(),
                $this->getMockForSort(),
            ]);

        $this->assertEquals([
            ['name' => 'ASC'],
            ['name' => 'ASC'],
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
                $this->getMockForComparison(),
                $this->getMockForComparison()
            ]);

        $this->assertEquals('id = 1 AND id = 1', $this->selectionFactory->where());
    }

    public function testWhereIfIdentityIsVoid()
    {
        $this->identityMock
            ->expects($this->once())
            ->method('isVoid')
            ->willReturn(true);

        $this->assertEquals('1', $this->selectionFactory->where());
    }

    public function testMakeFactoryMethods()
    {
        $this->assertInstanceOf(\G4\DataMapper\Engine\MySQL\MySQLComparisonFormatter::class, $this->selectionFactory->makeComparisonFormatter());
        $this->assertInstanceOf(\G4\DataMapper\Engine\MySQL\MySQLSortingFormatter::class, $this->selectionFactory->makeSortingFormatter());
    }

    private function getMockForComparison()
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Comparison::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComparison'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->willReturn('id = 1');

        return $mock;
    }

    private function getMockForSort()
    {
        $mock = $this->getMockBuilder(\G4\DataMapper\Common\Selection\Sort::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSort'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getSort')
            ->willReturn(['name' => 'ASC']);

        return $mock;
    }
}

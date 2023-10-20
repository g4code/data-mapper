<?php

use G4\DataMapper\Engine\Http\HttpSelectionFactory;
use G4\DataMapper\Common\Identity;
use G4\DataMapper\Exception\MethodNotValidForHttpEngineException;
use G4\DataMapper\Engine\Http\HttpComparisonFormatter;
use G4\DataMapper\Common\Selection\Comparison;

class HttpSelectionFactoryTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var HttpSelectionFactory
     */
    private $selectionFactory;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $identityMock;

    public function testFieldNames()
    {
        $this->expectException(MethodNotValidForHttpEngineException::class);

        $this->selectionFactory->fieldNames();
    }

    public function testGroup()
    {
        $this->expectException(MethodNotValidForHttpEngineException::class);

        $this->selectionFactory->group();
    }

    public function testSort()
    {
        $this->expectException(MethodNotValidForHttpEngineException::class);

        $this->selectionFactory->sort();
    }

    public function testWhereVoid()
    {
        $this->identityMock->expects($this->once())->method('isVoid')->willReturn(true);

        $this->assertEquals('', $this->selectionFactory->where());
    }

    public function testWhere()
    {
        $this->identityMock->expects($this->once())->method('isVoid')->willReturn(false);

        $this->identityMock
            ->expects($this->once())
            ->method('getComparisons')
            ->willReturn([
                $this->getMockForComparison(),
                $this->getMockForComparison(),
            ]);

        $this->assertEquals('id=1&id=1', $this->selectionFactory->where());
    }

    public function testLimit()
    {
        $this->expectException(MethodNotValidForHttpEngineException::class);

        $this->selectionFactory->limit();
    }

    public function testOffset()
    {
        $this->expectException(MethodNotValidForHttpEngineException::class);

        $this->selectionFactory->offset();
    }

    public function testMakeComparisonFormatter()
    {
        $this->assertInstanceOf(HttpComparisonFormatter::class, $this->selectionFactory->makeComparisonFormatter());
    }

    protected function setUp(): void
    {
        $this->identityMock = $this->getMockBuilder(Identity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new HttpSelectionFactory($this->identityMock);
    }

    protected function tearDown(): void
    {
        $this->identityMock     = null;
        $this->selectionFactory = null;
    }

    private function getMockForComparison()
    {
        $mock = $this->getMockBuilder(Comparison::class)
            ->disableOriginalConstructor()
            ->setMethods(['getComparison'])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method('getComparison')
            ->with($this->isInstanceOf(HttpComparisonFormatter::class))
            ->willReturn('id=1');

        return $mock;
    }
}

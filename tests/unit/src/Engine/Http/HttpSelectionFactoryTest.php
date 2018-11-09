<?php

use G4\DataMapper\Engine\Http\HttpSelectionFactory;
use G4\DataMapper\Common\Identity;
use G4\DataMapper\Exception\MethodNotValidForHttpEngineException;
use G4\DataMapper\Engine\Http\HttpComparisonFormatter;

class HttpSelectionFactoryTest extends PHPUnit_Framework_TestCase
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

    public function testWhere()
    {

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

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder(Identity::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new HttpSelectionFactory($this->identityMock);
    }

    protected function tearDown()
    {
        $this->identityMock     = null;
        $this->selectionFactory = null;
    }
}

<?php

use G4\DataMapper\Engine\MySQL\MySQLSelectionFactory;
use G4\DataMapper\Common\Selection\Identity;
use G4\DataMapper\Common\Selection\Comparision;

class MySQLSelectionFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MySQLSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;


    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Identity')
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new MySQLSelectionFactory($this->identityMock);
    }

    protected function tearDown()
    {
        $this->identityMock     = null;
        $this->selectionFactory = null;
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

    private function getMockForComparison()
    {
        $mock = $this->getMockBuilder('\G4\DataMapper\Common\Selection\Comparision')
            ->disableOriginalConstructor()
            ->setMethods(['getComparison'])
            ->getMock();

        $mock->expects($this->once())
            ->method('getComparison')
            ->willReturn('id = 1');

        return $mock;
    }
}
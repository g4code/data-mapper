<?php

use G4\DataMapper\Engine\MySQL\MySQLTransaction;

class MySQLTransactionTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;

    /**
     * @var MySQLTransaction
     */
    private $transaction;

    protected function setUp(): void
    {
        $this->adapterMock = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transaction = new MySQLTransaction($this->adapterMock);
    }

    protected function tearDown(): void
    {
        $this->adapterMock = null;

        $this->transaction = null;
    }

    public function testBegin()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('beginTransaction');

        $this->transaction->begin();
    }

    public function testBeginException()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('beginTransaction');

        $this->transaction->begin();

        $this->expectException(\G4\DataMapper\Exception\DatabaseOperationException::class);
        $this->expectExceptionCode(14011);
        $this->expectExceptionMessage('Database transaction has already started');

        $this->transaction->begin();
    }

    public function testCommit()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('commitTransaction');

        $this->transaction->commit();
    }

    public function testRollBack()
    {
        $this->adapterMock
            ->expects($this->once())
            ->method('rollBackTransaction');

        $this->transaction->rollBack();
    }
}

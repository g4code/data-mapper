<?php

use G4\DataMapper\Engine\MySQL\MySQLTransaction;

class MySQLTransactionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapterMock;

    /**
     * @var MySQLTransaction
     */
    private $transaction;

    protected function setUp()
    {
        $this->adapterMock = $this->getMockBuilder('\G4\DataMapper\Engine\MySQL\MySQLAdapter')
            ->disableOriginalConstructor()
            ->getMock();

        $this->transaction = new MySQLTransaction($this->adapterMock);
    }

    protected function tearDown()
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

        $this->expectException('\Exception');
        $this->expectExceptionCode(101);
        $this->expectExceptionMessage('Database transaction is already started');

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
<?php

use G4\DataMapper\Common\RawData;

class RawDataTest extends \PHPUnit\Framework\TestCase
{
    const ID_IDENTIFIER = 'id';

    private $data;

    private $rawData;

    private $total;

    protected function setUp(): void
    {
        $this->data = [
            [
                'id' => 1,
                'data' => 'lorem ipsum',
            ]
        ];

        $this->total = 9;

        $this->rawData = new RawData($this->data, $this->total);
    }

    protected function tearDown(): void
    {
        $this->data = null;
        $this->total = null;
        $this->rawData = null;
    }

    public function testCount()
    {
        $this->assertEquals(1, $this->rawData->count());
    }

    public function testGetAll()
    {
        $this->assertEquals($this->data, $this->rawData->getAll());
    }

    public function testGetOne()
    {
        $this->assertEquals($this->data[0], $this->rawData->getOne());
    }

    public function testGetTotal()
    {
        $this->assertEquals($this->total, $this->rawData->getTotal());
    }

    public function testGetAllWithIdIdentifier()
    {
        $data = [
            ['id' => 5, 'first_name' => 'Test', 'last_name' => 'User 1'],
            ['id' => 6, 'first_name' => 'Test', 'last_name' => 'User 2'],
            ['id' => 7, 'first_name' => 'Test', 'last_name' => 'User 3'],
        ];

        $expectedData = [
            5 => ['id' => 5, 'first_name' => 'Test', 'last_name' => 'User 1'],
            6 => ['id' => 6, 'first_name' => 'Test', 'last_name' => 'User 2'],
            7 => ['id' => 7, 'first_name' => 'Test', 'last_name' => 'User 3'],
        ];

        $this->assertEquals($expectedData, (new RawData($data, count($data)))->getAllDataWithIdIdentifier(self::ID_IDENTIFIER));
    }

    public function testGetAlWithIdIdentifierException()
    {
        $this->expectException(\G4\DataMapper\Exception\EmptyDataException::class);
        $this->expectExceptionMessage('Empty data.');
        $this->expectExceptionCode(10105);

        (new RawData([], count([])))->getAllDataWithIdIdentifier(self::ID_IDENTIFIER);
    }
}

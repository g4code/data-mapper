<?php

use G4\DataMapper\Engine\MySQL\MySQLAdapter;
use G4\DataMapper\Common\SingleValue;

class MySQLAdapterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var MySQLAdapter
     */
    private $adapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $clientMock;

    private $tableNameMock;


    protected function setUp()
    {
        $this->clientMock = $this->getMockBuilder(\Zend_Db_Adapter_Mysqli::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'insert',
                'delete',
                'update',
                'select',
                'fetchAll',
                'fetchOne',
                'query',
                'beginTransaction',
                'commit',
                'rollBack',
            ])
            ->getMock();

        $this->tableNameMock = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLTableName::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->tableNameMock
            ->expects($this->any())
            ->method('__toString')
            ->willReturn('data');

        $this->adapter = new MySQLAdapter($this->getMockForMySQLClientFactory());
    }

    protected function tearDown()
    {
        $this->adapter          = null;
        $this->clientMock       = null;
        $this->tableNameMock    = null;
    }

    public function testBeginTransaction()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('beginTransaction');

        $this->adapter->beginTransaction();
    }

    public function testCommitTransaction()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('commit');

        $this->adapter->commitTransaction();
    }

    public function testSimpleDelete()
    {
        $selectionFactoryMock = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryMock->expects($this->once())->method('where')->willReturn(1);
        $selectionFactoryMock->expects($this->once())->method('sort')->willReturn([]);
        $selectionFactoryMock->expects($this->once())->method('limit')->willReturn(0);

        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->with('DELETE FROM data WHERE 1');

        $this->adapter->delete($this->tableNameMock, $selectionFactoryMock);
    }

    public function testComplexDelete()
    {
        $selectionFactoryMock = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $selectionFactoryMock->expects($this->once())->method('where')->willReturn("name = 'test'");
        $selectionFactoryMock->expects($this->once())->method('sort')->willReturn(['name', 'email']);
        $selectionFactoryMock->expects($this->once())->method('limit')->willReturn(9);

        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->with("DELETE FROM data WHERE name = 'test' ORDER BY name, email LIMIT 9");

        $this->adapter->delete($this->tableNameMock, $selectionFactoryMock);
    }

    public function testEmptyDataForInsert()
    {
        $this->clientMock->expects($this->never())
            ->method('insert');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Empty data for insert');
        $this->adapter->insert($this->tableNameMock, $mappingStub);
    }

    public function testEmptyDataForUpdate()
    {
        $this->clientMock->expects($this->never())
            ->method('update');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $selectionFactoryStub = $this->getMock(G4\DataMapper\Common\SelectionFactoryInterface::class);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Empty data for update');
        $this->adapter->update($this->tableNameMock, $mappingStub, $selectionFactoryStub);
    }

    public function testInsert()
    {
        $this->adapter->setWrapInTransaction(true);

        $this->clientMock
            ->expects($this->once())
            ->method('insert');

        $mappingStub = $this->getMockForMappings();
        $mappingStub
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $this->adapter->insert($this->tableNameMock, $mappingStub);
    }

    public function testInsertBulk()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo("INSERT INTO data (`id`,`ts`) VALUES ('123','456'),('789','321')"));

        $mappingStubFirst = $this->getMockForMappings();
        $mappingStubFirst
            ->expects($this->any())
            ->method('map')
            ->willReturn([
                'id' => new SingleValue(123),
                'ts' => new SingleValue(456),
            ]);

        $mappingStubSecond = $this->getMockForMappings();
        $mappingStubSecond
            ->expects($this->any())
            ->method('map')
            ->willReturn([
                'id' => new SingleValue(789),
                'ts' => new SingleValue(321),
            ]);

        $this->adapter->insertBulk($this->tableNameMock, new \ArrayIterator([$mappingStubFirst, $mappingStubSecond]));
    }

    public function testInsertBulkException()
    {
        $this->expectException(\G4\DataMapper\Exception\EmptyDataException::class);
        $this->expectExceptionCode(10105);
        $this->expectExceptionMessage('Collection in insertBulk() must not be empty.');

        $this->adapter->insertBulk($this->tableNameMock, new \ArrayIterator([]));
    }

    public function testUpsertBulk()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo("INSERT INTO data (`id`,`ts`) VALUES ('123','456'),('789','321') ON DUPLICATE KEY UPDATE id=VALUES(id),ts=VALUES(ts)"));

        $mappingStubFirst = $this->getMockForMappings();
        $mappingStubFirst
            ->expects($this->any())
            ->method('map')
            ->willReturn([
                'id' => new SingleValue(123),
                'ts' => new SingleValue(456),
            ]);

        $mappingStubSecond = $this->getMockForMappings();
        $mappingStubSecond
            ->expects($this->any())
            ->method('map')
            ->willReturn([
                'id' => new SingleValue(789),
                'ts' => new SingleValue(321),
            ]);

        $this->adapter->upsertBulk($this->tableNameMock, new \ArrayIterator([$mappingStubFirst, $mappingStubSecond]));
    }

    public function testUpsertBulkException()
    {
        $this->expectException(\G4\DataMapper\Exception\EmptyDataException::class);
        $this->expectExceptionCode(10105);
        $this->expectExceptionMessage('Collection in upsertBulk() must not be empty.');

        $this->adapter->upsertBulk($this->tableNameMock, new \ArrayIterator([]));
    }

    public function testRollBack()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('rollBack');

        $this->adapter->rollBackTransaction();
    }

    public function testSelect()
    {
        $zendDbSelectMock = $this->getMockBuilder(\Zend_Db_Select::class)
            ->disableOriginalConstructor()
            ->getMock();
        $zendDbSelectMock
            ->expects($this->exactly(2))
            ->method('from')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->exactly(2))
            ->method('where')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->once())
            ->method('order')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->once())
            ->method('limit')
            ->willReturnSelf();
        $zendDbSelectMock
            ->expects($this->exactly(2))
            ->method('group')
            ->willReturnSelf();

        $this->clientMock
            ->expects($this->exactly(2))
            ->method('select')
            ->willReturn($zendDbSelectMock);
        $this->clientMock
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['data' => 1]]);
        $this->clientMock
            ->expects($this->once())
            ->method('fetchOne')
            ->willReturn(1);

        $selectionFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLSelectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertInstanceOf(\G4\DataMapper\Common\RawData::class, $this->adapter->select($this->tableNameMock, $selectionFactoryStub));
    }

    public function testUpdate()
    {
        $this->clientMock->expects($this->once())
            ->method('update');

        $mappingMock = $this->getMockForMappings();
        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn(['id' => 1]);

        $selectionFactoryMock = $this->getMockBuilder(\G4\DataMapper\Common\SelectionFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $selectionFactoryMock
            ->expects($this->once())
            ->method('where');

        $this->adapter->update($this->tableNameMock, $mappingMock, $selectionFactoryMock);
    }

    public function testUpsert()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->with($this->equalTo("INSERT INTO data (id, ts) VALUES (?, ?) ON DUPLICATE KEY UPDATE id = ?, ts = ?"), $this->equalTo([123, 456, 123, 456]));

        $mappingMock = $this->getMockForMappings();
        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn([
                'id' => 123,
                'ts' => 456,
            ]);

        $this->adapter->upsert($this->tableNameMock, $mappingMock);
    }


    public function testUpsertException()
    {
        $this->clientMock
            ->expects($this->never())
            ->method('query');

        $mappingMock = $this->getMockForMappings();
        $mappingMock
            ->expects($this->once())
            ->method('map')
            ->willReturn([]);

        $this->expectException(\G4\DataMapper\Exception\EmptyDataException::class);
        $this->expectExceptionMessage('Empty data for upsert');
        $this->expectExceptionCode(10105);

        $this->adapter->upsert($this->tableNameMock, $mappingMock);
    }

    public function testQueryForDelete()
    {
        $this->clientMock
            ->expects($this->once())
            ->method('query')
            ->willReturn(true);

        $this->assertNull($this->adapter->query('delete from table_name where 1'));
    }

    public function testQueryForSelect()
    {
        $query      = 'select * from table_name where 1 group by id limit 0,100';
        $countQuery = 'SELECT COUNT(*) AS cnt FROM table_name where 1 group by id';

        $this->clientMock
            ->expects($this->once())
            ->method('fetchAll')
            ->with($query)
            ->willReturn([['data' => 1]]);

        $this->clientMock
            ->expects($this->once())
            ->method('fetchOne')
            ->with($countQuery)
            ->willReturn(10);

        $this->assertInstanceOf(\G4\DataMapper\Common\RawData::class, $this->adapter->query($query));
    }

    public function testQueryWithEmptyQuery()
    {
        $this->expectException(\G4\DataMapper\Exception\EmptyDataException::class);
        $this->expectExceptionCode(10105);
        $this->expectExceptionMessage('Query can not be empty');

        $this->adapter->query('');
    }

    public function testQueryWithUnknown()
    {
        $this->expectException(\G4\DataMapper\Exception\InvalidValueException::class);
        $this->expectExceptionCode(14010);
        $this->expectExceptionMessage('Query does not match a known pattern (insert, delete, update, select)');

        $this->adapter->query('tralala');
    }

    public function testSetWrapInTransaction()
    {
        $this->adapter->setWrapInTransaction(true);

        $this->assertTrue($this->adapter->getWrapInTransaction(), true);
    }

    private function getMockForMySQLClientFactory()
    {
        $clientFactoryStub = $this->getMockBuilder(\G4\DataMapper\Engine\MySQL\MySQLClientFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $clientFactoryStub->method('create')
            ->willReturn($this->clientMock);

        return $clientFactoryStub;
    }

    private function getMockForMappings()
    {
        $mappingStub = $this->getMockBuilder(\G4\DataMapper\Common\MappingInterface::class)
            ->getMock();
        return $mappingStub;
    }
}

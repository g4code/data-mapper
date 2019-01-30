<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Exception\EmptyDataException;
use G4\DataMapper\Exception\InvalidValueException;
use G4\DataMapper\Exception\NotImplementedException;
use Zend_Db_Adapter_Abstract;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\RawData;

class MySQLAdapter implements AdapterInterface
{

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    private $client;

    /**
     * @var bool
     */
    private $useInnerTransactions;

    /**
     * @var bool
     */
    private $innerTransactionStarted;

    /**
     * @var bool
     */
    private $transactionActive;


    /**
     * MySQLAdapter constructor.
     * @param \G4\DataMapper\Engine\MySQL\MySQLClientFactory $clientFactory
     */
    public function __construct(MySQLClientFactory $clientFactory)
    {
        $this->client                   = $clientFactory->create();
        $this->useInnerTransactions     = false;
        $this->innerTransactionStarted  = false;
        $this->transactionActive        = false;
    }

    /**
     * @return Zend_Db_Adapter_Abstract | \Zend_Db_Adapter_Pdo_Mysql
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setWrapInTransaction($value)
    {
        $this->useInnerTransactions = $value;
        return $this;
    }

    public function getWrapInTransaction()
    {
        return $this->useInnerTransactions;
    }

    public function beginTransaction()
    {
        $this->transactionActive = true;
        $this->client->beginTransaction();
    }

    public function commitTransaction()
    {
        $this->client->commit();
        $this->transactionActive = false;
    }

    public function rollBackTransaction()
    {
        $this->client->rollBack();
        $this->transactionActive = false;
    }

    private function innerTransactionBegin()
    {
        if (!$this->useInnerTransactions) {
            return;
        }

        $this->innerTransactionStarted = false;

        if (!$this->transactionActive) {
            $this->client->beginTransaction();
            $this->innerTransactionStarted = true;
        }
    }

    private function innerTransactionEnd()
    {
        if (!$this->useInnerTransactions) {
            return;
        }

        if (!$this->transactionActive && $this->innerTransactionStarted) {
            $this->client->commit();
            $this->innerTransactionStarted = false;
        }
    }

    /**
     * @param CollectionNameInterface $table
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function delete(CollectionNameInterface $table, SelectionFactoryInterface $selectionFactory)
    {
        $order      = $selectionFactory->sort();
        $limit      = $selectionFactory->limit();

        $query = sprintf(
            'DELETE FROM %s WHERE %s %s %s',
            (string) $table,
            $selectionFactory->where(),
            (count($order) === 0 ? '' : " ORDER BY " . implode(', ', $order)),
            (empty($limit) ? '' : " LIMIT {$limit}")
        );

        $filteredQuery = preg_replace('/\s+/', ' ', trim($query));

        $this->innerTransactionBegin();
        $this->client->query($filteredQuery);
        $this->innerTransactionEnd();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param array $data
     */
    public function deleteBulk(CollectionNameInterface $collectionName, array $data)
    {
        throw new NotImplementedException();
    }

    /**
     * @param CollectionNameInterface $table
     * @param MappingInterface $mappings
     * @throws EmptyDataException
     */
    public function insert(CollectionNameInterface $table, MappingInterface $mappings)
    {
        $data = $mappings->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for insert.');
        }

        $this->innerTransactionBegin();
        $this->client->insert((string) $table, $data);
        $this->innerTransactionEnd();
    }

    /**
     * @param CollectionNameInterface $table
     * @param \ArrayIterator $mappingsCollection
     * @throws EmptyDataException
     */
    public function insertBulk(CollectionNameInterface $table, \ArrayIterator $mappingsCollection)
    {
        if (count($mappingsCollection) === 0) {
            throw new EmptyDataException('Collection in insertBulk() must not be empty.');
        }

        $mappingsCollection->rewind();
        $currentMapping = $mappingsCollection->current();
        $fields = "`" . implode("`,`", array_keys($currentMapping->map())) . "`";
        $values = [];

        foreach ($mappingsCollection as $mapping) {
            $quotedValues = array();
            foreach ($mapping->map() as $value) {
                $quotedValues[] = $value === null || ($value instanceof SingleValue && $value->isNull())
                    ? 'NULL'
                    : (string) new Quote(new SingleValue($value));
            }
            $values[] = "(" .implode(",", $quotedValues) . ")";
        }

        $tableName = (string) $table;

        $query = "INSERT INTO {$tableName} ({$fields}) VALUES " . implode(',', $values);

        $this->innerTransactionBegin();
        $this->client->query($query);
        $this->innerTransactionEnd();
    }

    /**
     * @param CollectionNameInterface $table
     * @param \ArrayIterator $mappingsCollection
     * @throws EmptyDataException
     */
    public function upsertBulk(CollectionNameInterface $table, \ArrayIterator $mappingsCollection)
    {
        if (count($mappingsCollection) === 0) {
            throw new EmptyDataException('Collection in upsertBulk() must not be empty.');
        }

        $mappingsCollection->rewind();
        $currentMapping = $mappingsCollection->current();
        $fields = "`" . implode("`,`", array_keys($currentMapping->map())) . "`";
        $values = [];

        foreach ($mappingsCollection as $mapping) {
            $quotedValues = array();
            foreach ($mapping->map() as $value) {
                $quotedValues[] = (string) new Quote(new SingleValue($value));
            }
            $values[] = "(" .implode(",", $quotedValues) . ")";
        }

        $tableName = (string) $table;
        $updatePartOfQuery = "";
        foreach ($currentMapping->map() as $key => $value) {
            $updatePartOfQuery .= "{$key}=VALUES({$key}),";
        }
        $updatePartOfQueryFormatted = rtrim($updatePartOfQuery, ",") ;

        $query = "INSERT INTO {$tableName} ({$fields}) VALUES " . implode(',', $values) .
            " ON DUPLICATE KEY UPDATE ".$updatePartOfQueryFormatted;

        $this->innerTransactionBegin();
        $this->client->query($query);
        $this->innerTransactionEnd();
    }

    /**
     * @param CollectionNameInterface $table
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function select(CollectionNameInterface $table, SelectionFactoryInterface $selectionFactory)
    {
        $selectForData = $this->client
            ->select()
            ->from((string) $table, $selectionFactory->fieldNames())
            ->where($selectionFactory->where())
            ->order($selectionFactory->sort())
            ->limit($selectionFactory->limit(), $selectionFactory->offset())
            ->group($selectionFactory->group());

        $data = $this->client->fetchAll($selectForData);

        $selectForTotal = $this->client
            ->select()
            ->from((string) $table, 'COUNT(*) AS cnt')
            ->where($selectionFactory->where())
            ->group($selectionFactory->group());

        $total = $this->client->fetchOne($selectForTotal);

        return new RawData($data, $total);
    }

    /**
     * @param CollectionNameInterface $table
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     * @throws EmptyDataException
     */
    public function update(
        CollectionNameInterface $table,
        MappingInterface $mapping,
        SelectionFactoryInterface $selectionFactory
    ) {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for update');
        }

        $this->innerTransactionBegin();
        $this->client->update((string) $table, $data, $selectionFactory->where());
        $this->innerTransactionEnd();
    }

    /**
     * @param CollectionNameInterface $table
     * @param array $data
     * @throws NotImplementedException
     */
    public function updateBulk(CollectionNameInterface $table, array $data)
    {
        throw new NotImplementedException();
    }

    /**
     * @param CollectionNameInterface $table
     * @param MappingInterface $mapping
     * @throws EmptyDataException
     */
    public function upsert(CollectionNameInterface $table, MappingInterface $mapping)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for upsert');
        }

        $fields = implode(", ", array_keys($data));
        $values = implode(", ", array_fill(1, count($data), "?"));
        $update = implode(" = ?, ", array_keys($data)) . " = ?";

        $tableName = (string) $table;

        $query = "INSERT INTO {$tableName} ({$fields}) VALUES ({$values}) ON DUPLICATE KEY UPDATE {$update}";

        $this->innerTransactionBegin();
        $this->client->query($query, array_merge(array_values($data), array_values($data)));
        $this->innerTransactionEnd();
    }

    /**
     * @param string $query
     * @return RawData|void
     * @throws EmptyDataException
     * @throws InvalidValueException
     */
    public function query($query)
    {
        if (empty($query)) {
            throw new EmptyDataException('Query can not be empty');
        }

        if (preg_match('~^\s*(insert\sinto|delete\sfrom|update\s)~usxi', $query) === 1) {
            $this->client->query($query);
            return;
        }

        if (preg_match('~^\s*(select\s)~usxi', $query) === 1) {
            $data = $this->client->fetchAll(substr_replace(trim($query), 'SQL_CALC_FOUND_ROWS ', 7, 0));
            $total = $this->client->fetchOne('SELECT FOUND_ROWS()');

            return new RawData($data, $total);
        }

        throw new InvalidValueException('Query does not match a known pattern (insert, delete, update, select)');
    }
}

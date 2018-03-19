<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\Bulk;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\SingleValue;
use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use Zend_Db_Adapter_Abstract;
use Zend_Db;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\RawData;

class MySQLAdapter implements AdapterInterface
{

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    private $client;

    private $useInnerTransactions = false;

    private $innerTransactionStarted = false;

    private $transactionActive = false;


    public function __construct(MySQLClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
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


    public function delete(CollectionNameInterface $table, SelectionFactoryInterface $selectionFactory)
    {
        $this->innerTransactionBegin();
        $this->client->delete((string) $table, $selectionFactory->where());
        $this->innerTransactionEnd();
    }

    public function insert(CollectionNameInterface $table, MappingInterface $mappings)
    {
        $data = $mappings->map();

        if (empty($data)) {
            throw new \Exception('Empty data for insert', 101);
        }

        $this->innerTransactionBegin();
        $this->client->insert((string) $table, $data);
        $this->innerTransactionEnd();
    }

    public function insertBulk(CollectionNameInterface $table, \ArrayIterator $mappingsCollection)
    {
        if (count($mappingsCollection) === 0) {
            throw new \Exception('Collection in insertBulk() must not be empty.', 101);
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

        $query = "INSERT INTO {$tableName} ({$fields}) VALUES " . implode(',', $values);

        $this->innerTransactionBegin();
        $this->client->query($query);
        $this->innerTransactionEnd();
    }

    public function upsertBulk(CollectionNameInterface $table, \ArrayIterator $mappingsCollection)
    {
        if (count($mappingsCollection) === 0) {
            throw new \Exception('Collection in upsertBulk() must not be empty.', 101);
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

    public function update(CollectionNameInterface $table, MappingInterface $mapping, SelectionFactoryInterface $selectionFactory)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new \Exception('Empty data for update', 101);
        }

        $this->innerTransactionBegin();
        $this->client->update((string) $table, $data, $selectionFactory->where());
        $this->innerTransactionEnd();
    }

    public function upsert(CollectionNameInterface $table, MappingInterface $mapping)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new \Exception('Empty data for upsert', 101);
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

    public function query($query)
    {
        if (empty($query)) {
            throw new \Exception('Query cannot be empty', 101);
        }

        if (preg_match('~^\s*(insert\sinto|delete\sfrom|update\s)~usxi', $query) === 1) {
            $this->client->query($query);
            return;
        }

        if (preg_match('~^\s*(select\s)~usxi', $query) === 1) {
            $data = $this->client->fetchAll($query);
            $selectForTotal = preg_replace(
                [
                    '~^\s*select\s(.+)\sfrom~Uix',
                    '~limit\s\d+\,(\s|)\d+(\s|$)~Uix',
                ],
                [
                    'SELECT COUNT(*) AS cnt FROM',
                    ' ',
                ],
                $query
            );
            $total = $this->client->fetchOne(trim($selectForTotal));
            return new RawData($data, $total);
        }

        throw new \Exception('Query does not match a known pattern (insert, delete, update, select)', 101);
    }
}

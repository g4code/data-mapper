<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\MappingInterface;
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


    public function __construct(MySQLClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    public function delete($table, SelectionFactoryInterface $selectionFactory)
    {
        $this->client->delete($table, $selectionFactory->where());
    }

    public function insert($table, MappingInterface $mappings)
    {
        $data = $mappings->map();

        if (empty($data)) {
            throw new \Exception('Empty data for insert', 101);
        }

        $this->client->insert($table, $data);
    }

    public function select($table, SelectionFactoryInterface $selectionFactory)
    {
        $selectForData = $this->client
            ->select()
            ->from($table, $selectionFactory->fieldNames())
            ->where($selectionFactory->where())
            ->order($selectionFactory->sort())
            ->limit($selectionFactory->limit(), $selectionFactory->offset())
            ->group($selectionFactory->group());

        $data = $this->client->fetchAll($selectForData);

        $selectForTotal = $this->client
            ->select()
            ->from($table, 'COUNT(*) AS cnt')
            ->where($selectionFactory->where())
            ->group($selectionFactory->group());

        $total = $this->client->fetchOne($selectForTotal);

        return new RawData($data, $total);
    }

    public function update($table, MappingInterface $mapping, SelectionFactoryInterface $selectionFactory)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new \Exception('Empty data for update', 101);
        }

        $this->client->update($table, $data, $selectionFactory->where());
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

        if (preg_match('~^\s*(select\s|show\s)~usxi', $query) === 1) {
            $data = $this->client->fetchAll($query);
            return new RawData($data, 0);
        }

        throw new \Exception('Query does not match a known pattern (insert, delete, update, select)', 101);
    }
}
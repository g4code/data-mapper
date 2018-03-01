<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Exception\EmptyDataException;
use G4\ValueObject\Dictionary;

class ElasticsearchAdapter implements AdapterInterface
{
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    private $client;

    public function __construct(ElasticsearchClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function delete(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory)
    {
        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_DELETE)
            ->setId($this->extractIdValue($selectionFactory->where()))
            ->execute();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     * @throws EmptyDataException
     */
    public function insert(CollectionNameInterface $collectionName, MappingInterface $mapping)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for insert.');
        }

        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_POST)
            ->setBody($data)
            ->execute();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param \ArrayIterator $mappingsCollection
     */
    public function insertBulk(CollectionNameInterface $collectionName, \ArrayIterator $mappingsCollection)
    {
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param \ArrayIterator $mappingsCollection
     */
    public function upsertBulk(CollectionNameInterface $collectionName, \ArrayIterator $mappingsCollection)
    {
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function select(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory)
    {
        $query = [
            'from'    => $selectionFactory->offset(),
            'size'    => $selectionFactory->limit(),
            'query'   => $selectionFactory->where(),
            'sort'    => $selectionFactory->sort(),
            '_source' => $selectionFactory->fieldNames()
        ];

        $data = $this->client
            ->setIndex($collectionName)
            ->setBody($query)
            ->search();

        return new RawData($this->formatData($data->getResponse()), $data->getTotalItemsCount());
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     * @throws EmptyDataException
     */
    public function update(CollectionNameInterface $collectionName, MappingInterface $mapping, SelectionFactoryInterface $selectionFactory)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for update.');
        }

        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_PUT)
            ->setId($this->extractIdValue($selectionFactory->where()))
            ->setBody($data)
            ->execute();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     */
    public function upsert(CollectionNameInterface $collectionName, MappingInterface $mapping)
    {
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function query($query)
    {
    }

    private function extractIdValue($data)
    {
        return (new Dictionary($data))->getFromDeeperLevels('bool', 'must', '0', 'match', 'id');
    }

    private function formatData($data)
    {
        $formattedData = [];

        foreach($data['hits'] as $item) {
            $formattedData []= $item['_source'];
        }

        return $formattedData;
    }
}

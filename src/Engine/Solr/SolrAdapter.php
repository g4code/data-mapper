<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Exception\EmptyDataException;

class SolrAdapter implements AdapterInterface
{
    const METHOD_ADD     = 'add';
    const METHOD_DELETE  = 'delete';
    const IDENTIFIER_KEY = 'id';

    private $client;

    public function __construct(SolrClientFactory $clientFactory)
    {
        $this->client = $clientFactory->create();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function delete(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory)
    {
        $this->client->setCollection($collectionName)->setDocument([
            self::METHOD_DELETE => ['query' => $selectionFactory->where()],
        ])->update();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     */
    public function insert(CollectionNameInterface $collectionName, MappingInterface $mapping)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for insert.');
        }

        $this->client->setCollection($collectionName)->setDocument($this->formatData($data))->update();
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
        $data = $this->client
            ->setCollection($collectionName)
            ->setQuery([
                'q'     => $selectionFactory->where(),
                'fl'    => $selectionFactory->fieldNames(),
                'rows'  => $selectionFactory->limit(),
                'sort'  => $selectionFactory->sort(),
                'start' => $selectionFactory->offset(),
                'wt'    => 'json',
            ])
            ->select();

        return new RawData($data, $this->client->getTotalItemsCount());
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function update(CollectionNameInterface $collectionName, MappingInterface $mapping, SelectionFactoryInterface $selectionFactory = null)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for update.');
        }

        $this->client->setCollection($collectionName)->setDocument($data)->update();
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

    /**
     * @param $data
     * @return array
     */
    private function formatData(array $data)
    {
        foreach ($data as $key => $value) {
            if ($key != self::IDENTIFIER_KEY) {
                $data[$key] = [self::METHOD_ADD => $value];
            }
        }

        return $data;
    }
}

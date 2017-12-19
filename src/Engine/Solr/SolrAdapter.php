<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SelectionFactoryInterface;

class SolrAdapter implements AdapterInterface
{
    const METHOD_ADD     = 'add';
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
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     */
    public function insert(CollectionNameInterface $collectionName, MappingInterface $mapping)
    {
        $data = $mapping->map();

        if (empty($data)) {
            throw new \Exception('Empty data for insert', 101);
        }
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
            throw new \Exception('Empty data for update', 101);
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
}

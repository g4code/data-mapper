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

    const FIELDS        = 'fields';
    const QUERY         = 'q';
    const LIMIT         = 'rows';
    const SORT          = 'sort';
    const OFFSET        = 'start';
    const RESPONSE_TYPE = 'wt';

    const JSON_RESPONSE_TYPE = 'json';

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

        $this->client->setCollection($collectionName)->setDocument([$data])->update();
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
                self::QUERY         => $selectionFactory->where(),
                self::FIELDS        => $selectionFactory->fieldNames(),
                self::LIMIT         => $selectionFactory->limit(),
                self::SORT          => $selectionFactory->sort(),
                self::OFFSET        => $selectionFactory->offset(),
                self::RESPONSE_TYPE => self::JSON_RESPONSE_TYPE
            ])
            ->select();

        return new RawData($data['response']['docs'], $this->client->getTotalItemsCount());
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
}

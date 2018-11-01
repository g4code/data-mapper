<?php

namespace G4\DataMapper\Engine\Solr;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Exception\EmptyDataException;
use G4\DataMapper\Exception\NotImplementedException;

class SolrAdapter implements AdapterInterface
{
    const METHOD_ADD     = 'add';
    const METHOD_DELETE  = 'delete';
    const IDENTIFIER_KEY = 'id';

    const FIELDS        = 'fl';
    const QUERY         = 'q';
    const LIMIT         = 'rows';
    const SORT          = 'sort';
    const OFFSET        = 'start';
    const RESPONSE_TYPE = 'wt';

    const JSON_RESPONSE_TYPE = 'json';

    const OPERATION_SET = 'set';

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
     * @param array $data
     */
    public function deleteBulk(CollectionNameInterface $collectionName, array $data)
    {
        throw new NotImplementedException();
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
        throw new NotImplementedException();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param \ArrayIterator $mappingsCollection
     */
    public function upsertBulk(CollectionNameInterface $collectionName, \ArrayIterator $mappingsCollection)
    {
        throw new NotImplementedException();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function select(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory)
    {
        $query = [
            self::QUERY         => $selectionFactory->where(),
            self::FIELDS        => $selectionFactory->fieldNames(),
            self::LIMIT         => $selectionFactory->limit(),
            self::SORT          => $selectionFactory->sort(),
            self::OFFSET        => $selectionFactory->offset(),
            self::RESPONSE_TYPE => self::JSON_RESPONSE_TYPE,
        ];

        $this->client
            ->setCollection($collectionName)
            ->setQuery(array_merge($query, $selectionFactory->getGeodistParameters()))
            ->select();

        return new RawData($this->client->getDocuments(), $this->client->getTotalItemsCount());
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function update(
        CollectionNameInterface $collectionName,
        MappingInterface $mapping,
        SelectionFactoryInterface $selectionFactory
    ) {
        if (empty($mapping->map())) {
            throw new EmptyDataException('Empty data for update.');
        }

        $data = $this->formatData($mapping->map());

        //TODO: Refactor id value extraction.
        $idValue = explode(':', $selectionFactory->where());

        $data['id'] = $idValue[1];

        $this->client->setCollection($collectionName)->setDocument([$data])->update();
    }

    public function updateBulk(CollectionNameInterface $collectionName, array $data)
    {
        if (empty($data)) {
            throw new EmptyDataException('Empty data for bulk update');
        }

        $this->client->setCollection($collectionName)->setDocument($data)->update();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     */
    public function upsert(CollectionNameInterface $collectionName, MappingInterface $mapping)
    {
        throw new NotImplementedException();
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function query($query)
    {
        throw new NotImplementedException();
    }

    /**
     * @param array $data
     * @return array
     */
    private function formatData(array $data)
    {
        $formattedData = [];

        foreach ($data as $key => $value) {
            $formattedData[$key] = [self::OPERATION_SET => $value];
        }

        return $formattedData;
    }
}

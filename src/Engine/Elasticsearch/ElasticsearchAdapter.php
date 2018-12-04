<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\IdentifiableMapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Exception\EmptyDataException;
use G4\ValueObject\Dictionary;
use G4\DataMapper\Exception\NotImplementedException;
use G4\DataMapper\Exception\ClientException;

class ElasticsearchAdapter implements AdapterInterface
{
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const ELASTIC_BULK_ACTION_UPDATE = 'update';
    const ELASTIC_BULK_ACTION_DELETE = 'delete';
    const ELASTIC_PARAM_ID           = '_id';
    const ELASTIC_PAYLOAD_TYPE_DOC   = 'doc';

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
     * @param array $data
     */
    public function deleteBulk(CollectionNameInterface $collectionName, array $data)
    {
        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_POST)
            ->setBody($this->prepareBulkDeleteData(...$data))
            ->executeBulk();
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
            ->setId($data['id'])
            ->setBody($data)
            ->execute();
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
        $body = [
            'from'    => $selectionFactory->offset(),
            'size'    => $selectionFactory->limit(),
            'query'   => $selectionFactory->where(),
            'sort'    => $selectionFactory->sort(),
            '_source' => $selectionFactory->fieldNames()
        ];

        $data = $this->client
            ->setIndex($collectionName)
            ->setBody($body)
            ->search();

        if ($this->client->hasError()) {
            throw new ClientException($this->client->getErrorMessage() . ', body=' . json_encode($body));
        }

        return new RawData($this->formatData($data->getResponse()), $data->getTotalItemsCount());
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     * @throws EmptyDataException
     */
    public function update(
        CollectionNameInterface $collectionName,
        MappingInterface $mapping,
        SelectionFactoryInterface $selectionFactory
    ) {
        $data = $mapping->map();

        if (empty($data)) {
            throw new EmptyDataException('Empty data for update.');
        }

        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_POST)
            ->setId($this->extractIdValue($selectionFactory->where()))
            ->setBody(['doc' => $data])
            ->update();
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param IdentifiableMapperInterface[] $data
     */
    public function updateBulk(CollectionNameInterface $collectionName, array $data)
    {
        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_POST)
            ->setBody($this->prepareBulkUpdateData(...$data))
            ->executeBulk();
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
     * @param CollectionNameInterface $collectionName
     */
    public function refresh(CollectionNameInterface $collectionName)
    {
        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_POST)
            ->refresh();
    }

    private function extractIdValue($data)
    {
        return (new Dictionary($data))->getFromDeeperLevels('bool', 'must', '0', 'match', 'id');
    }

    private function formatData($data)
    {
        $formattedData = [];

        if (array_key_exists('hits', $data)) {
            foreach ($data['hits'] as $item) {
                $formattedData []= $item['_source'];
            }
        }

        return $formattedData;
    }

    /**
     * @param IdentifiableMapperInterface[] ...$mappings
     * @return array
     * @throws EmptyDataException
     */
    private function prepareBulkUpdateData(IdentifiableMapperInterface ... $mappings)
    {
        $data = [];
        foreach ($mappings as $mapping) {
            $data[] = [
                self::ELASTIC_BULK_ACTION_UPDATE => [
                    self::ELASTIC_PARAM_ID => $mapping->getId()
                ]
            ];
            $data[] = [
                self::ELASTIC_PAYLOAD_TYPE_DOC => $mapping->map()
            ];
        }

        if (empty($data)) {
            throw new EmptyDataException('Empty data for update.');
        }

        return $data;
    }

    /**
     * @param IdentifiableMapperInterface[] ...$mappings
     * @return array
     * @throws EmptyDataException
     */
    private function prepareBulkDeleteData(IdentifiableMapperInterface ... $mappings)
    {
        $data = [];
        foreach ($mappings as $mapping) {
            $data[] = [
                self::ELASTIC_BULK_ACTION_DELETE => [
                    self::ELASTIC_PARAM_ID => $mapping->getId()
                ]
            ];
        }

        if (empty($data)) {
            throw new EmptyDataException('Empty data for delete.');
        }

        return $data;
    }
}

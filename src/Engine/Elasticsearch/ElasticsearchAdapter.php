<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Common\IdentifiableMapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Domain\TotalCount;
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
    const ELASTIC_PAYLOAD_INDEX   = '_index';

    private $client;

    private $perPage;

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
            ->insert();
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
        $body = $this->getBody($selectionFactory);

        $data = $this->client
            ->setIndex($collectionName)
            ->setBody($body)
            ->search();

        if ($this->client->hasError()) {
            throw new ClientException(sprintf(
                "error=%s, body=%s, url=%s",
                $this->client->getErrorMessage(),
                json_encode($body),
                (string) $this->client->getUrl()
            ));
        }

        return new RawData($this->formatData($data->getResponse()), $data->getTotalItemsCount());
    }


    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface[] $data
     * @return RawData
     */
    public function multiSelect(CollectionNameInterface $collectionName, array $data)
    {
        $queries = $this->formatMultiSearchQueries($data);

        $body = $this->formatMultiSearchBody($queries);

        $this->client
            ->setIndex($collectionName)
            ->setBody($body);

        $this->client->multiSearch();


        if ($this->client->hasError()) {
            throw new ClientException(sprintf(
                "error=%s, body=%s, url=%s",
                $this->client->getErrorMessage(),
                json_encode($body),
                (string) $this->client->getUrl()
            ));
        }

        return new RawData($this->formatMultiData($this->client->getResponse()), $this->client->getTotalItemsCount());
    }

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function count(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory)
    {
        $body = ['query' => $selectionFactory->where()];

        $data = $this->client
            ->setIndex($collectionName)
            ->setBody($body)
            ->count();

        if ($this->client->hasError()) {
            throw new ClientException(sprintf(
                "error=%s, body=%s, url=%s",
                $this->client->getErrorMessage(),
                json_encode($body),
                (string) $this->client->getUrl()
            ));
        }

        return new RawData([], $data->getTotalItemsCount());
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

        $id = $this->extractIdValue($selectionFactory->where());

        $this->client
            ->setIndex($collectionName)
            ->setMethod(self::METHOD_POST)
            ->setId($id)
            ->setBody(['doc' => $data])
            ->update();

        if ($this->client->hasError()) {
            throw new ClientException(sprintf(
                "error=%s, body=%s, url=%s, id: %s",
                $this->client->getErrorMessage(),
                json_encode($data),
                (string) $this->client->getUrl(),
                $id
            ));
        }
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
                if (isset($item['_type'])) {
                    $formattedData[] = ['_index' => $item['_index'], '_type' => $item['_type']] + $item['_source'];
                } else {
                    $formattedData[] = ['_index' => $item['_index']] + $item['_source'];
                }
            }
        }

        return $formattedData;
    }

    /**
     * @param IdentifiableMapperInterface[] ...$mappings
     * @return array
     * @throws EmptyDataException
     */
    private function prepareBulkUpdateData(IdentifiableMapperInterface ...$mappings)
    {
        $data = [];
        foreach ($mappings as $mapping) {
            $data[] = [
                self::ELASTIC_BULK_ACTION_UPDATE => [
                    self::ELASTIC_PARAM_ID => $mapping->getId(),
                    self::ELASTIC_PAYLOAD_INDEX => (string) $this->client->getIndex(),
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
    private function prepareBulkDeleteData(IdentifiableMapperInterface ...$mappings)
    {
        $data = [];
        foreach ($mappings as $mapping) {
            $data[] = [
                self::ELASTIC_BULK_ACTION_DELETE => [
                    self::ELASTIC_PARAM_ID => $mapping->getId(),
                    self::ELASTIC_PAYLOAD_INDEX => (string) $this->client->getIndex(),
                ]
            ];
        }

        if (empty($data)) {
            throw new EmptyDataException('Empty data for delete.');
        }

        return $data;
    }

    public function simpleQuery($query)
    {
        throw new NotImplementedException();
    }

    /** @param SelectionFactoryInterface[] $listOfEsSelectionFactories
     */
    private function formatMultiSearchQueries(array $listOfEsSelectionFactories)
    {
        $queries = [];

        /** @var ElasticsearchSelectionFactory $selectionFactory */

        foreach ($listOfEsSelectionFactories as $selectionFactory) {
            $queries[] = '{}' . PHP_EOL . json_encode($this->getBody($selectionFactory), true);
        }
        return $queries;
    }

    private function formatMultiData(array $data)
    {
        $multiFormattedData = [];
        foreach ($data as $singleItem) {
            $multiFormattedData[] = (new RawData($this->formatData($singleItem), (new TotalCount($singleItem))->getValue()));
        }

        return $multiFormattedData;
    }

    private function formatMultiSearchBody(array $queries)
    {
        return implode(PHP_EOL, $queries) . PHP_EOL;
    }

    private function getBody(SelectionFactoryInterface $selectionFactory)
    {
        return [
            'from'    => $selectionFactory->offset(),
            'size'    => $selectionFactory->limit(),
            'query'   => $selectionFactory->where(),
            'sort'    => $selectionFactory->sort(),
            '_source' => $selectionFactory->fieldNames()
        ];
    }
}

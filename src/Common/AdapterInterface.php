<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Exception\EmptyDataException;
use G4\DataMapper\Exception\InvalidValueException;

interface AdapterInterface
{

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function delete(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory);

    /**
     * @param CollectionNameInterface $collectionName
     * @param array $data
     */
    public function deleteBulk(CollectionNameInterface $collectionName, array $data);

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     */
    public function insert(CollectionNameInterface $collectionName, MappingInterface $mapping);

    /**
     * @param CollectionNameInterface $collectionName
     * @param \ArrayIterator $mappingsCollection
     */
    public function insertBulk(CollectionNameInterface $collectionName, \ArrayIterator $mappingsCollection);

    /**
     * @param CollectionNameInterface $collectionName
     * @param \ArrayIterator $mappingsCollection
     */
    public function upsertBulk(CollectionNameInterface $collectionName, \ArrayIterator $mappingsCollection);

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function select(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory);

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function update(
        CollectionNameInterface $collectionName,
        MappingInterface $mapping,
        SelectionFactoryInterface $selectionFactory
    );

    /**
     * @param CollectionNameInterface $collectionName
     * @param array $data
     */
    public function updateBulk(CollectionNameInterface $collectionName, array $data);

    /**
     * @param CollectionNameInterface $collectionName
     * @param MappingInterface $mapping
     */
    public function upsert(CollectionNameInterface $collectionName, MappingInterface $mapping);

    /**
     * @param string $query
     * @return mixed
     */
    public function query($query);

    /**
     * @param string $query
     * @return SimpleRawData|void
     * @throws EmptyDataException
     * @throws InvalidValueException
     */
    public function simpleQuery($query);

    /**
     * @param CollectionNameInterface $collectionName
     * @param array $data
     * @return RawData
     */
    public function multiSelect(CollectionNameInterface $collectionName, array $data);


    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function count(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory);
}

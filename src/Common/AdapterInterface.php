<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\RawData;

interface AdapterInterface
{

    /**
     * @param CollectionNameInterface $collectionName
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function delete(CollectionNameInterface $collectionName, SelectionFactoryInterface $selectionFactory);

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
    public function update(CollectionNameInterface $collectionName, MappingInterface $mapping, SelectionFactoryInterface $selectionFactory);

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
}

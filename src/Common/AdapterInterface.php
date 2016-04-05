<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\RawData;

interface AdapterInterface
{

    /**
     * @param $type
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function delete($type, SelectionFactoryInterface $selectionFactory);

    /**
     * @param $type
     * @param MappingInterface $mapping
     */
    public function insert($type, MappingInterface $mapping);

    /**
     * @param $type
     * @param \ArrayIterator $mappingsCollection
     */
    public function insertBulk($type, \ArrayIterator $mappingsCollection);

    /**
     * @param $type
     * @param SelectionFactoryInterface $selectionFactory
     * @return RawData
     */
    public function select($type, SelectionFactoryInterface $selectionFactory);

    /**
     * @param $table
     * @param MappingInterface $mapping
     * @param SelectionFactoryInterface $selectionFactory
     */
    public function update($table, MappingInterface $mapping, SelectionFactoryInterface $selectionFactory);

    /**
     * @param $table
     * @param MappingInterface $mapping
     */
    public function upsert($table, MappingInterface $mapping);

    /**
     * @param string $query
     * @return mixed
     */
    public function query($query);
}
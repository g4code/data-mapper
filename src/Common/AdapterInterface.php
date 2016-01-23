<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\SelectionFactoryInterface;

interface AdapterInterface
{

    public function delete($type, SelectionFactoryInterface $selectionFactory);

    public function insert($type, MappingInterface $mapping);

    public function select($type, SelectionFactoryInterface $selectionFactory);

    public function update($table, MappingInterface $mapping);
}
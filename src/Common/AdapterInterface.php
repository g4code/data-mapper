<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;

interface AdapterInterface
{

    public function delete($type, MappingInterface $mapping);

    public function insert($type, MappingInterface $mapping);

    public function select();

    public function update($table, MappingInterface $mapping);
}
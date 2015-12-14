<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\MappingInterface;

interface AdapterInterface
{

    public function delete($type, MappingInterface $mappings);

    public function insert($type, MappingInterface $mappings);

    public function select();

    public function update($table, array $data, array $identifiers);
}
<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;

interface MapperInterface
{

    public function delete(MappingInterface $mappings);

    public function find(IdentityInterface $identity);

    public function insert(MappingInterface $mappings);

    public function update(MappingInterface $mappings);
}
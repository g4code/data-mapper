<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;

interface MapperInterface
{

    public function delete(IdentityInterface $mappings);

    public function find(IdentityInterface $identity);

    public function insert(MappingInterface $mapping);

    public function update(MappingInterface $mapping, IdentityInterface $identity);
}
<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\SelectionIdentityInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\Factory\ReconstituteInterface;

interface MapperInterface
{

    public function delete(MappingInterface $mappings);

    public function findAll(SelectionIdentityInterface $identity, ReconstituteInterface $factory);

    public function findOne(SelectionIdentityInterface $identity, ReconstituteInterface $factory);

    public function insert(MappingInterface $mappings);

    public function update(MappingInterface $mappings);
}
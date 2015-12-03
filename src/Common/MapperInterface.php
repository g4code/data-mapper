<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\SelectionIdentityInterface;

interface MapperInterface
{

    public function delete(MappingsInterface $mappings);

    public function findAll(SelectionIdentityInterface $identity, ReconstituteInterface $factory);

    public function findOne(SelectionIdentityInterface $identity, ReconstituteInterface $factory);

    public function insert(MappingsInterface $mappings);

    public function update(MappingsInterface $mappings);
}
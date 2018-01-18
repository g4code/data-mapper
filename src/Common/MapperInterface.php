<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Common\RawData;

interface MapperInterface
{

    /**
     * @param IdentityInterface $identity
     */
    public function delete(IdentityInterface $identity);

    /**
     * @param IdentityInterface $identity
     * @return RawData
     */
    public function find(IdentityInterface $identity);

    /**
     * @param MappingInterface $mapping
     */
    public function insert(MappingInterface $mapping);

    /**
     * @param MappingInterface $mapping
     */
    public function upsert(MappingInterface $mapping);

    /**
     * @param MappingInterface $mapping
     * @param IdentityInterface $identity
     */
    public function update(MappingInterface $mapping, IdentityInterface $identity);

    /**
     * @param mixed $query
     * @return mixed
     */
    public function query($query);
}

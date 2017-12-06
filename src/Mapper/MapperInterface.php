<?php

namespace G4\DataMapper\Mapper;

use G4\DataMapper\Domain\DomainAbstract;
use G4\DataMapper\Selection\Identity;

interface MapperInterface
{
    public function delete(Identity $identity);

    public function findAll(Identity $identity);

    public function findOne(Identity $identity);

    public function insert(DomainAbstract $domain);

    public function update(DomainAbstract $domain);
}

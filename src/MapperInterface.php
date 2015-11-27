<?php

namespace G4\DataMapper;

use G4\DataMapper\SelectionIdentityInterface;

interface MapperInterface
{

    public function delete();

    public function findAll(SelectionIdentityInterface $identity);

    public function findOne(SelectionIdentityInterface $identity);

    public function insert();

    public function update();
}
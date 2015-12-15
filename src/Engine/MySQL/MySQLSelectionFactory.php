<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\SelectionIdentityInterface;

class MySQLSelectionFactory implements SelectionFactoryInterface
{

    private $selectionIdentity;

    public function __construct(SelectionIdentityInterface $selectionIdentity)
    {
        $this->selectionIdentity = $selectionIdentity;
    }

    public function fields()
    {

    }

    public function group()
    {

    }

    public function sort()
    {

    }

    public function where()
    {

    }

    public function limit()
    {

    }
}
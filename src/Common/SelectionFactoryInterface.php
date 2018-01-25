<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\IdentityInterface;

interface SelectionFactoryInterface
{
    public function fieldNames();

    public function group();

    public function limit();

    public function offset();

    public function sort();

    public function where();
}

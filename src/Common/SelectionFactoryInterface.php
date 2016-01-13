<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\SelectionIdentityInterface;

interface SelectionFactoryInterface
{

    public function __construct(SelectionIdentityInterface $identity);

    public function fieldNames();

    public function group();

    public function limit();

    public function offset();

    public function sort();

    public function where();

}
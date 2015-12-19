<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\SelectionIdentityInterface;

interface SelectionFactoryInterface
{

    public function __construct(SelectionIdentityInterface $identity);

    public function fields();

    public function group();

    public function sort();

    public function where();

    public function limit();

}
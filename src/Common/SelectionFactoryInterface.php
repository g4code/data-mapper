<?php

namespace G4\DataMapper\Common;

interface SelectionFactoryInterface
{

    public function group();

    public function sort();

    public function where();

    public function limit();

}
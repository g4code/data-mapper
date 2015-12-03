<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\AdapterInterface;

class MySQLMapper
{

    private $adapter;

    private $type;

    public function __construct(AdapterInterface $adapter, $type)
    {
        $this->adapter = $adapter;
        $this->type    = $type;
    }



}
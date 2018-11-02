<?php

namespace G4\DataMapper\Engine\Http;

use G4\DataMapper\Common\AdapterInterface;

class HttpMapper
{

    private $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
}
<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\Common\Errors;

class NotImplementedException extends \Exception
{
    public function __construct()
    {
        parent::__construct(Errors::MESSAGE_NOT_IMPLEMENTED, Errors::CODE_NOT_IMPLEMENTED);
    }
}

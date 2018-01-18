<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\Common\Errors;

class TypeNameException extends \Exception
{

    protected $code = Errors::CODE_TYPE_NAME_NOT_STRING;

    protected $message = Errors::MESSAGE_TYPE_NAME_NOT_STRING;


    public function __construct()
    {
    }
}

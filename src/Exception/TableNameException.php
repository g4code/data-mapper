<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\Common\Errors;

class TableNameException extends \Exception
{

    protected $code = Errors::CODE_TABLE_NAME_NOT_STRING;

    protected $message = Errors::MESSAGE_TABLE_NAME_NOT_STRING;


    public function __construct(){}

}
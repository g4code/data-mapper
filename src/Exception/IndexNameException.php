<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\Common\Errors;

class IndexNameException extends \Exception
{

    protected $code = Errors::CODE_INDEX_NAME_NOT_STRING;

    protected $message = Errors::MESSAGE_INDEX_NAME_NOT_STRING;


    public function __construct(){}

}
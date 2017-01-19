<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\Common\Errors;

class NotImplementedException extends \Exception
{

    protected $code = Errors::CODE_NOT_IMPLEMENTED;

    protected $message = Errors::MESSAGE_NOT_IMPLEMENTED;


    public function __construct(){}

}
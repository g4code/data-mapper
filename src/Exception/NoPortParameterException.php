<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class NoPortParameterException extends \Exception
{
    public function __construct($message = '')
    {
        if(!$message) {
            $message = 'No port parameter.';
        }

        parent::__construct($message, ErrorCode::NO_PORT_PARAMETER);
    }
}

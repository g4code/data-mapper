<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class NoHostParameterException extends \Exception
{
    public function __construct($message = '')
    {
        if(!$message) {
            $message = 'No host parameter.';
        }

        parent::__construct($message, ErrorCode::NO_HOST_PARAMETER);
    }
}

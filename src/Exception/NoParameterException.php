<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class NoParameterException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = 'NO_PARAMETER';
        }

        parent::__construct($message, ErrorCode::NO_PARAMETER);
    }
}

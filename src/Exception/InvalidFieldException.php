<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class InvalidFieldException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = 'INVALID_FIELD';
        }

        parent::__construct($message,ErrorCode::INVALID_FIELD);
    }
}

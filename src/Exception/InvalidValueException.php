<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class InvalidValueException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = 'INVALID_VALUE_EXCEPTION';
        }

        parent::__construct($message,ErrorCode::INVALID_VALUE);
    }
}

<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class InvalidValueTypeException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = 'WRONG_VALUE_TYPE_EXCEPTION';
        }

        parent::__construct($message,ErrorCode::WRONG_VALUE_TYPE);
    }
}

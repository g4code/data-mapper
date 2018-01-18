<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class NotAnInstanceException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = ErrorMessage::NOT_AN_INSTANCE;
        }

        parent::__construct($message, ErrorCode::NOT_AN_INSTANCE);
    }
}

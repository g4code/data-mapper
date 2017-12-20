<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class EmptyDataException extends \Exception
{
    public function __construct($message = '')
    {
        if(!$message) {
            $message = ErrorMessage::EMPTY_DATA;
        }

        parent::__construct($message, ErrorCode::EMPTY_DATA);
    }
}

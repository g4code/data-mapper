<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;
use G4\DataMapper\ErrorMessages as ErrorMessage;

class OrderNotInMapException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = ErrorMessage::ORDER_NOT_IN_MAP;
        }

        parent::__construct($message, ErrorCode::ORDER_NOT_IN_MAP);
    }
}

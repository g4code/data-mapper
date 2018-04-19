<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class DatabaseOperationException extends \Exception
{
    public function __construct($message = '')
    {
        if (!$message) {
            $message = 'DATABASE_OPERATION_EXCEPTION';
        }

        parent::__construct($message, ErrorCode::DATABASE_OPERATION);
    }
}

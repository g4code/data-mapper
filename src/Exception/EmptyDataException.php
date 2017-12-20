<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class EmptyDataException extends \Exception
{
    public function __construct($message = '')
    {
        if(!$message) {
            $message = 'Empty data.';
        }

        parent::__construct($message, ErrorCode::EMPTY_DATA);
    }
}

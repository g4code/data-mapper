<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class OrderNotInMapException extends \Exception
{
    public function __construct($message = '')
    {
        if(!$message) {
            $message = 'Order is not in map.';
        }

        parent::__construct($message, ErrorCode::ORDER_NOT_IN_MAP);
    }
}

<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class BulkOperationException extends \Exception
{
    const MESSAGE = 'Bulk operation has encountered an error: %s';

    public function __construct($value)
    {
        parent::__construct(
            sprintf(self::MESSAGE, \var_export($value, true)),
            ErrorCode::BULK_OPERATION
        );
    }
}

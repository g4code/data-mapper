<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class TableNameException extends \Exception
{
    const MESSAGE = 'Table name is not a string';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCode::TABLE_NAME_NOT_STRING);
    }
}

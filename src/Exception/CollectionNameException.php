<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class CollectionNameException extends \Exception
{
    const MESSAGE = 'Collection name is not a string';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCode::COLLECTION_NAME_NOT_STRING);
    }
}

<?php

namespace G4\DataMapper\Exception;

use Exception;
use G4\DataMapper\ErrorCodes;

class HttpPathException extends Exception
{

    const MESSAGE = 'Http path is not a string';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, ErrorCodes::HTTP_PATH_NOT_STRING);
    }
}

<?php

namespace G4\DataMapper\Exception;

use Exception;
use G4\DataMapper\ErrorCodes;

class MethodNotValidForHttpEngineException extends Exception
{

    const MESSAGE = 'Method %s:%ss is not valid for Http Engine';

    public function __construct($className, $methodName)
    {
        parent::__construct(
            sprintf(self::MESSAGE, $className, $methodName),
            ErrorCodes::METHOD_NOT_VALID_FOR_HTTP_ENGINE
        );
    }
}

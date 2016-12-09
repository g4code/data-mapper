<?php

namespace G4\DataMapper\Exception;

class TableNameException extends \Exception
{

    public function __construct($message, $code, \Exception $previous)
    {
        parent::__construct($message, $code, $previous);
    }

}
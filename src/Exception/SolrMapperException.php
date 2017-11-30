<?php

namespace G4\DataMapper\Exception;

use G4\DataMapper\ErrorCodes as ErrorCode;

class SolrMapperException extends \Exception
{
    const MESSAGE = 'Solr Mapper has encountered an error: %s';

    public function __construct($value)
    {
        parent::__construct(
            sprintf(self::MESSAGE, \var_export($value, true)),
            ErrorCode::DATA_MAPPER_ERROR
        );
    }
}
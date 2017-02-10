<?php

namespace G4\DataMapper\Selection\Solr\Consts;

class Query
{
    const ASCENDING     = 'asc';
    const DESCENDING    = 'desc';

    const BRACKET_CLOSE = ']';
    const BRACKET_OPEN  = '[';

    const CURLY_BRACKET_CLOSE = '}';
    const CURLY_BRACKET_OPEN  = '{';

    const CONNECTOR_AND = 'AND';
    const CONNECTOR_OR  = 'OR';

    const COLON         = ':';
    const MAX_DISTANCE  = 100000;
    const TO            = 'TO';
    const WILDCARD      = '*';

}
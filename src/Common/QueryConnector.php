<?php

namespace G4\DataMapper\Common;

class QueryConnector
{
    const ROUND_BRACKET_OPEN   = '(';
    const ROUND_BRACKET_CLOSE  = ')';

    const SQUARE_BRACKET_CLOSE = ']';
    const SQUARE_BRACKET_OPEN  = '[';

    const CURLY_BRACKET_CLOSE  = '}';
    const CURLY_BRACKET_OPEN   = '{';

    const COLON                = ':';
    const WILDCARD             = '*';

    const CONNECTOR_TO         = 'TO';
    const CONNECTOR_OR         = 'OR';

    const EMPTY_SPACE          = ' ';
    const COMMA                = ',';

    const GREATER_THAN          = 'gt';
    const GREATER_THAN_OR_EQUAL = 'gte';
    const LESS_THAN             = 'lt';
    const LESS_THAN_OR_EQUAL    = 'lte';

    const MATCH                   = 'match';
    const RANGE                   = 'range';
    const TERMS                   = 'terms';
    const TYPE                    = 'type';
    const NAME_WILDCARD           = 'wildcard';
    const NAME_QUERY_STRING       = 'query_string';
    const NAME_QUERY_STRING_QUERY = 'query';

    const FORMAT                = 'format';
    const EPOCH_SECOND          = 'epoch_second';
}

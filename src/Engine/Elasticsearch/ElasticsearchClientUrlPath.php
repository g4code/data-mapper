<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\ValueObject\Url;

class ElasticsearchClientUrlPath
{
    public static function update(Url $url, $index, $indexType, $id)
    {
        return $url->path($index, $indexType, $id, ElasticsearchClient::UPDATE);
    }

    public static function bulk(Url $url, $index, $indexType, $id)
    {
        return $url->path($index, $indexType, ElasticsearchClient::BULK);
    }
}

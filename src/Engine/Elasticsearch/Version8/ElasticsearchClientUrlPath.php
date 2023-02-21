<?php

namespace G4\DataMapper\Engine\Elasticsearch\Version8;

use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClient;
use G4\ValueObject\Url;

class ElasticsearchClientUrlPath
{
    public static function update(Url $url, $index, $indexType, $id)
    {
        return $url->path($index, ElasticsearchClient::UPDATE, $id);
    }

    public static function bulk(Url $url, $index, $indexType, $id)
    {
        return $url->path(ElasticsearchClient::BULK);
    }
}

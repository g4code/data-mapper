<?php

namespace G4\DataMapper\Engine\Elasticsearch;

use G4\ValueObject\Url;

class ElasticsearchClient
{

    private $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }
}

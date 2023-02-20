<?php

namespace G4\DataMapper\Profiler\Ticker;

use G4\DataMapper\Profiler\Elasticsearch\Formatter;
use G4\DataMapper\Engine\Elasticsearch\ElasticsearchClientFactory;

class ProfilerTickerElasticsearch extends \G4\Profiler\Ticker\TickerAbstract
{
    const NAME = 'data_mapper_elasticsearch_1.x';
    const TYPE = 'es';

    private static $instance;

    public function __construct()
    {
    }

    public function __clone()
    {
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return Formatter
     */
    public function getDataFormatterInstance()
    {
        return new Formatter();
    }

    public function setInfo($uniqueId, $info, $method, $query)
    {
        $this->getDataPart($uniqueId)->setInfo($info);
        $this->getDataPart($uniqueId)->setMethod($method);
        $this->getDataPart($uniqueId)->setQuery($query);
    }
}

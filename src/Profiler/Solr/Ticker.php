<?php

namespace G4\DataMapper\Profiler\Solr;

class Ticker extends \G4\Profiler\Ticker\TickerAbstract
{

    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }


    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }


    /**
     * @return Formatter
     */
    public function getDataFormatterInstance()
    {
        return new Formatter();
    }

    public function getName()
    {
        return 'data_mapper_solr';
    }

    /**
     * @param string $uniqueId
     * @param array $info
     */
    public function setInfo($uniqueId, array $info)
    {
        $this->getDataPart($uniqueId)->setInfo($info);
    }
}

<?php

namespace G4\DataMapper\Profiler\Mysql;

use G4\DI\Container as DI;

class Ticker extends \G4\Profiler\Ticker\TickerAbstract
{

    private static $instance;

    private function __construct() {}

    private function __clone() {}

    /**
     * @return \G4\DataMapper\Profiler\Mysql\Ticker
     */
    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'data_mapper_mysql';
    }

    /**
     * @return int
     */
    public function getTotalElapsedTime()
    {
        return $this->getDbProfiler()->getTotalElapsedSecs();
    }

    /**
     * @return int
     */
    public function getTotalNumQueries()
    {
        return $this->getDbProfiler()->getTotalNumQueries();
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        if ($this->getDbProfiler()->getTotalNumQueries()) {
            foreach ($this->getDbProfiler()->getQueryProfiles() as $queryProfile) {
                $queries[] = [
                    'elapsed_time' => $this->getDataFormatterInstance()->getFormattedTime($queryProfile->getElapsedSecs()),
                    'query'        => $queryProfile->getQuery()
                ];
            }
        }
        return isset($queries) ? $queries : [];
    }

    /**
     * @return \Zend_Db_Profiler
     */
    private function getDbProfiler()
    {
        return DI::get('db')->getProfiler();
    }
}
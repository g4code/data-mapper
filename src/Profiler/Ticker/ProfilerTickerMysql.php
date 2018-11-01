<?php


namespace G4\DataMapper\Profiler\Ticker;

use G4\DataMapper\Engine\MySQL\MySQLClientFactory;
use G4\Profiler\Ticker\TickerAbstract;

//TODO: Drasko - tmp solution - fix after Profiler refactoring is done!
class ProfilerTickerMysql extends TickerAbstract
{

    const NAME = 'data_mapper_mysql_1.x';

    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    private $db;

    public function __construct(MySQLClientFactory $clientFactory)
    {
        $this->db = $clientFactory->create();
    }

    private function __clone()
    {
    }

    public function getName()
    {
        return self::NAME;
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
        if ($this->getDbProfiler()->getTotalNumQueries() && $this->getDbProfiler()->getQueryProfiles()) {
            foreach ($this->getDbProfiler()->getQueryProfiles() as $queryProfile) {
                $queries[] = [
                    'elapsed_time' => $this->getDataFormatterInstance()
                        ->getFormattedTime($queryProfile->getElapsedSecs()),
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
        return  $this->db->getProfiler();
    }
}

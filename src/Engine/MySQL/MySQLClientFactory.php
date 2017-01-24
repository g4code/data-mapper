<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\Factory\CreateInterface;

class MySQLClientFactory implements CreateInterface
{

    const ADAPTER       = 'PDO_mysql';
    const CHARSET       = 'utf8';
    const CHARSET_NAMES = 'SET NAMES utf8;';

    /**
     * @var array
     */
    private $params;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->filterParams($params);
    }

    /**
     * @return \Zend_Db_Adapter_Abstract
     */
    public function create()
    {
        $client = \Zend_Db::factory(self::ADAPTER, $this->params);
        $client
            ->getProfiler()->setEnabled(true);
        return $client;
    }

    /**
     * @param array $params
     * @throws \Exception
     */
    private function filterParams(array $params)
    {
        if (empty($params['host'])) {
            throw new \Exception('No host param', 101);
        }
        if (empty($params['port'])) {
            throw new \Exception('No port param', 101);
        }
        if (empty($params['username'])) {
            throw new \Exception('No username param', 101);
        }
        if(isset($params['password']) && is_null($params['password'])){
            throw new \Exception('No password param', 101);
        }
        if (empty($params['dbname'])) {
            throw new \Exception('No dbname param', 101);
        }
        $this->params = [
            'host'     => $params['host'],
            'port'     => $params['port'],
            'username' => $params['username'],
            'password' => $params['password'],
            'dbname'   => $params['dbname'],
            'charset'  => self::CHARSET,
            'driver_options' => [
                1002 => self::CHARSET_NAMES,
            ]
        ];
    }
}
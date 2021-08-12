<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Exception\NoHostParameterException;
use G4\DataMapper\Exception\NoParameterException;
use G4\Factory\CreateInterface;
use Zend_Db_Adapter_Abstract;
use Zend_Db;
use Exception;

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
     * @var Zend_Db_Adapter_Abstract
     */
    private $client;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->filterParams($params);
    }

    /**
     * @return Zend_Db_Adapter_Abstract
     */
    public function create()
    {
        if (!$this->client instanceof Zend_Db_Adapter_Abstract) {
            $this->client = Zend_Db::factory(self::ADAPTER, $this->params);
            $this->client
                ->getProfiler()->setEnabled(true);
        }
        return $this->client;
    }

    /**
     * @param array $params
     * @throws Exception
     */
    private function filterParams(array $params)
    {
        if (empty($params['host'])) {
            throw new NoParameterException('No host param');
        }
        if (empty($params['port'])) {
            throw new NoParameterException('No port param');
        }
        if (empty($params['username'])) {
            throw new NoParameterException('No username param');
        }
        if (!array_key_exists('password', $params) || $params['password'] === null) {
            throw new NoParameterException('No password param');
        }
        if (empty($params['dbname'])) {
            throw new NoParameterException('No dbname param');
        }
        $this->params = [
            'host'     => $params['host'],
            'port'     => $params['port'],
            'username' => $params['username'],
            'password' => $params['password'],
            'dbname'   => $params['dbname'],
            'charset'  => isset($params['charset']) ? $params['charset'] : self::CHARSET,
            'driver_options' => [
                1002 => isset($params['driver_options'][1002]) ? $params['driver_options'][1002] : self::CHARSET_NAMES,
            ],
        ];
    }
}

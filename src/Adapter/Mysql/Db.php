<?php

namespace G4\DataMapper\Adapter\Mysql;

class Db
{
    private $db;

    public function __construct($config)
    {
        $this->db = \Zend_Db::factory($config['adapter'], $config['params']);
        $this->db->getProfiler()->setEnabled(true);
    }

    public function get()
    {
        return $this->db;
    }

}
<?php

namespace G4\DataMapper\Factory\Domain;

abstract class DomainAbstract
{

    /**
     * @var array
     */
    protected $_data;

    protected $_domainModel;

    protected $_domainModelName;


    public function __construct()
    {
        $this->_domainModel = null;

        $this->_domainModelFactory();
    }

    /**
     *
     * @param array| $data
     */
    public function createObject($data = null)
    {
        $this->setData($data);

        if ($this->hasData() && $this->hasDomainModelInstance()) {

            $this->_objectFactory();
        }

        return $this->getDomainModel();
    }


    public function getDataProperty($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }


    public function getDomainModel()
    {
        return $this->_domainModel;
    }


    public function hasData()
    {
        return is_array($this->_data) && !empty($this->_data);
    }


    public function hasDomainModelInstance()
    {
        return $this->_domainModel !== null;
    }


    /**
     *
     * @param \Zend_Db_Table_Row|array $data
     */
    public function setData($data)
    {
        $this->_data = \G4\DataMapper\Db\Db::isTableRowInstance($data) ? $data->toArray() : $data;

        return $this;
    }


    abstract protected function _objectFactory();


    /**
     *
     */
    protected function _domainModelFactory()
    {
        if (empty($this->_domainModelName) || !class_exists($this->_domainModelName)) {

            throw new \Exception('Domain model class does not exit: '.$this->_domainModelName, 500);
        }

        $this->_domainModel = new $this->_domainModelName();
    }
}
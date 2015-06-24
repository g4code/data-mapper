<?php

namespace G4\DataMapper\Factory\Domain;

abstract class DomainAbstract implements DomainInterface
{

    //TODO: Drasko: change to private after interface implementation in all child classes!
    protected $_data;

    //TODO: Drasko: change to private after interface implementation in all child classes!
    protected $_domainModel;

    //TODO: Drasko: remove this after interface implementation in all child classes!
    protected $_domainModelName;

    //TODO: Drasko: remove this after interface implementation in all child classes!
    public function __construct()
    {
        $this->getDomainModel();
    }

    /**
     *
     * @param $data
     */
    public function createObject($data = null)
    {
        $this->setData($data);

        $this->hasData()
            ? $this->objectFactory()
            : $this->nullObjectFactory();

        return $this->_domainModel;
    }

    public function getData()
    {
        return $this->_data;
    }

    public function getDataProperty($name)
    {
        return $this->hasDataProperty($name) ? $this->_data[$name] : null;
    }

    public function getDomainModel()
    {
        if (!$this->hasDomainModelInstance()) {
            $this->_domainModel = $this->getDomainModelInstance();
        }
        return $this->_domainModel;
    }

    public function hasDataProperty($name)
    {
        return isset($this->_data[$name]);
    }

    public function nullObjectFactory()
    {
        $this->_domainModel = null;
    }

    public function setDataProperty($name, $value)
    {
        $this->_data[$name] = $value;
    }

    private function hasData()
    {
        return is_array($this->_data) && !empty($this->_data);
    }

    private function hasDomainModelInstance()
    {
        return $this->_domainModel !== null;
    }

    /**
     * @param \Zend_Db_Table_Row|array $data
     */
    private function setData($data)
    {
        $this->_data = \G4\DataMapper\Db\Db::isTableRowInstance($data) ? $data->toArray() : $data;
        return $this;
    }


    //TODO: Drasko: remove this after interface implementation in all child classes!
    protected function _domainModelFactory()
    {
        if (empty($this->_domainModelName) || !class_exists($this->_domainModelName)) {
            throw new \Exception('Domain model class does not exit: ' . $this->_domainModelName, 500);
        }
        return new $this->_domainModelName();
    }

    //TODO: Drasko: remove this after interface implementation in all child classes!
    public function getDomainModelInstance()
    {
        return $this->_domainModelFactory();
    }

    //TODO: Drasko: remove this after interface implementation in all child classes!
    public function objectFactory()
    {
        $this->_objectFactory();
    }

}
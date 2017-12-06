<?php

namespace G4\DataMapper\Domain;

use G4\DataMapper\Mapper\MapperInterface;
use G4\DataMapper\Mapper\Save;

abstract class DomainAbstract
{
    const METHOD_GET_ID_KEY = 'getIdKey';

    /**
     * @var int
     */
    protected $_id = null;

    /**
     * @var string
     */
    protected static $_idKey = 'id';

    /**
     * @var Save
     */
    protected $_mapperSave = null;

    abstract public function getRawData();

    public static function getIdKey()
    {
        return static::$_idKey;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function hasId()
    {
        return $this->_id !== null;
    }

    /**
     * @param int $id
     *
     * @return DomainAbstract
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }
}

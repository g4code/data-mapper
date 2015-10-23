<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;

class IdentityAbstract
{

    private $_currentField = null;

    private $_fields = array();

    /**
     * @var int
     */
    private $limit;

    /**
     * @var array
     */
    private $orderBy;

    /**
     * @var int
     */
    private $page;


    public function __construct()
    {
        //TODO: change this!!!;
        $this->limit = '';
        $this->orderBy = [];
        $this->page = '';
    }

    /**
     * @return Identity
     */
    public function field($fieldname)
    {
        if (!$this->isVoid() && $this->_currentField->isIncomplete()) {
            throw new \Exception("Incomplete field");
        }

        if (isset($this->_fields[$fieldname])) {
            $this->_currentField = $this->_fields[$fieldname];
        } else {
            $this->_currentField = new IdentityField($fieldname);
            $this->_fields[$fieldname] = $this->_currentField;
        }

        return $this;
    }

    /**
     * @return G4\DataMapper\Selection\IdentityField
     */
    public function getCurrentField()
    {
        return $this->_currentField;
    }

    public function getComps()
    {
        $ret = array();

        foreach ($this->_fields as $key => $field) {
            $ret = array_merge($ret, $field->getComps());
        }

        return $ret;
    }

    public function getId($fieldName)
    {
        return isset($this->_fields[$fieldName]) ? $this->_fields[$fieldName]->getCompEq() : null;
    }

    public function isVoid()
    {
        return empty($this->_fields);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return boolean
     */
    public function hasOrderBy()
    {
        return !empty($this->orderBy);
    }

    /**
     * @return IdentityAbstract
     */
    public function setLimit($value)
    {
        $this->limit = $value;
        return $this;
    }

    /**
     * @return IdentityAbstract
     */
    public function setOrderBy($param, $value)
    {
        $this->orderBy[$param] = $value;
        return $this;
    }

    /**
     * @return IdentityAbstract
     */
    public function setPage($value)
    {
        $this->page = $value;
        return $this;
    }

    /**
     * @return Identity
     */
    public function operator($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->_currentField->add($symbol, $value);

        return $this;
    }

    public function operatorAttach($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->_currentField->attach($symbol, $value);

        return $this;
    }
}
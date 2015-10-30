<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;
use G4\DataMapper\Selection\IdentityInterface;

abstract class IdentityAbstract implements IdentityInterface
{

    /**
     * @var IdentityField
     */
    private $currentField;

    /**
     * @var array
     */
    private $fields;

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
        $this->currentField = null;
        $this->fields       = [];
        $this->limit        = ''; //TODO: Drasko: change this!!!;
        $this->orderBy      = [];
        $this->page         = ''; //TODO: Drasko: change this!!!;
    }

    /**
     * @param string $fieldname
     * @throws \Exception
     * @return IdentityAbstract
     */
    public function field($fieldname)
    {
        if (!$this->isVoid() && $this->currentField->isIncomplete()) {
            throw new \Exception("Incomplete field");
        }

        if (isset($this->fields[$fieldname])) {
            $this->currentField = $this->fields[$fieldname];
        } else {
            $this->currentField = new IdentityField($fieldname);
            $this->fields[$fieldname] = $this->currentField;
        }

        return $this;
    }

    /**
     * @return IdentityField
     */
    public function getCurrentField()
    {
        return $this->currentField;
    }

    /**
     * @return array
     */
    public function getComps()
    {
        $ret = [];

        foreach ($this->fields as $key => $field) {
            $ret = array_merge($ret, $field->getComps());
        }

        return $ret;
    }

    /**
     * @param string $fieldName
     * @return mixed
     */
    public function getId($fieldName)
    {
        return isset($this->fields[$fieldName]) ? $this->fields[$fieldName]->getCompEq() : null;
    }

    /**
     * @return boolean
     */
    public function isVoid()
    {
        return empty($this->fields);
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
     * @param int $value
     * @return IdentityAbstract
     */
    public function setLimit($value)
    {
        $this->limit = $value;
        return $this;
    }

    /**
     * @param string $param
     * @param string $value
     * @return IdentityAbstract
     */
    public function setOrderBy($param, $value)
    {
        $this->orderBy[$param] = $value;
        return $this;
    }

    /**
     * @param int $value
     * @return IdentityAbstract
     */
    public function setPage($value)
    {
        $this->page = $value;
        return $this;
    }

    /**
     * @param string $symbol
     * @param string $value
     * @throws \Exception
     * @return IdentityAbstract
     */
    public function operator($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->currentField->add($symbol, $value);

        return $this;
    }

    /**
     * @param string $symbol
     * @param string $value
     * @throws \Exception
     * @return IdentityAbstract
     */
    public function operatorAttach($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->currentField->attach($symbol, $value);

        return $this;
    }
}
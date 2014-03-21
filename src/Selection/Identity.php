<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;

//TODO: Drasko - This needs refactoring - large class!
class Identity
{
    private $_currentField = null;

    private $_customContainer = array();

    private $_enforce = array();

    private $_fields = array();

    private $_limit = '';

    private $_page = '';

    private $_orderBy = array();

    public function __construct($field = null, array $enforce = null)
    {
        if (!is_null($enforce)) {
            $this->_enforce = $enforce;
        }

        if (!is_null($field)) {
            $this->field($field);
        }
    }

    public function enforceField($fieldname)
    {
        if (!in_array($fieldname, $this->_enforce) && !empty($this->_enforce)) {
            $forcelist = implode(', ', $this->_enforce);
            throw new \Exception("{$fieldname} is not a legal field ($forcelist)");
        }
    }

    /**
     * @return Identity
     */
    public function field($fieldname)
    {
        if (!$this->isVoid() && $this->_currentField->isIncomplete()) {
            throw new \Exception("Incomplete field");
        }

        $this->enforceField($fieldname);

        if (isset($this->_fields[$fieldname])) {
            $this->_currentField = $this->_fields[$fieldname];
        } else {
            $this->_currentField = new IdentityField($fieldname);
            $this->_fields[$fieldname] = $this->_currentField;
        }

        return $this;
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

    public function getLimit()
    {
        return $this->_limit;
    }

    public function getObjectFields()
    {
        return $this->_enforce;
    }

    public function getPage()
    {
        return $this->_page;
    }

    public function getOrderBy()
    {
        return $this->_orderBy;
    }

    public function isVoid()
    {
        return empty($this->_fields);
    }

    /**
     * @return Identity
     */
    public function eq($value = null)
    {
        $this->_operator("=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function neq($value = null)
    {
        $this->_operator("<>", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function gt($value = null)
    {
        $this->_operator(">", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function in($fields = array())
    {
        $fields = "('" . implode("', '", $fields) . "')";

        $this->_operator('IN', $fields);
        return $this;
    }

    /**
     * @return Identity
     */
    public function like($value = null)
    {
        $this->_operator("LIKE", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function lt($value = null)
    {
        $this->_operator("<", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function le($value = null)
    {
        $this->_operator("<=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function ge($value = null)
    {
        $this->_operator(">=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function setLimit($value)
    {
        $this->_limit = $value;
        return $this;
    }

    /**
     * @return Identity
     */
    public function setPage($value)
    {
        $this->_page = $value;
        return $this;
    }

    /**
     * @return Identity
     */
    public function setOrderBy($param, $value)
    {
        $this->_orderBy[$param] = $value;
        return $this;
    }

    public function __call($name, $args)
    {
        $method = array();
        preg_match('~([a-z]+)(.*)~', $name, $method);

        switch ($method[1]) {
            case 'set' :
                $this->_customContainer[$method[2]] = $args[0];
                break;
            case 'get' :
                return isset($this->_customContainer[$method[2]]) ? $this->_customContainer[$method[2]] : null;
                break;
        }
    }

    /**
     * @return Identity
     */
    protected function _operator($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->_currentField->add($symbol, $value);

        return $this;
    }
}

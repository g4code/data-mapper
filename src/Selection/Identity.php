<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;
use G4\DataMapper\Selection\IdentityAbstract;

//TODO: Drasko - This needs refactoring - large class!
class Identity extends IdentityAbstract
{
    private $_currentField = null;

    private $_customContainer = array();

    private $_fields = array();

    private $_page = '';

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

    public function getPage()
    {
        return $this->_page;
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
    public function likeWildcardLeft($value = null)
    {
        $this->_operator("LIKE", "%{$value}");
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardRight($value = null)
    {
        $this->_operator("LIKE", "{$value}%");
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardBoth($value = null)
    {
        $this->_operator("LIKE", "%{$value}%");
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
    public function setPage($value)
    {
        $this->_page = $value;
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

    protected function _operatorAttach($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->_currentField->attach($symbol, $value);

        return $this;
    }
}

<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;
use G4\DataMapper\Selection\IdentityAbstract;

//TODO: Drasko - This needs refactoring - large class!
class Identity extends IdentityAbstract
{
    private $_currentField = null;

    private $_fields = array();

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
     * @return Identity
     */
    public function eq($value = null)
    {
        $this->operator("=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function neq($value = null)
    {
        $this->operator("<>", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function gt($value = null)
    {
        $this->operator(">", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function in($fields = array())
    {
        $fields = "('" . implode("', '", $fields) . "')";

        $this->operator('IN', $fields);
        return $this;
    }

    /**
     * @return Identity
     */
    public function like($value = null)
    {
        $this->operator("LIKE", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardLeft($value = null)
    {
        $this->operator("LIKE", "%{$value}");
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardRight($value = null)
    {
        $this->operator("LIKE", "{$value}%");
        return $this;
    }

    /**
     * @return Identity
     */
    public function likeWildcardBoth($value = null)
    {
        $this->operator("LIKE", "%{$value}%");
        return $this;
    }

    /**
     * @return Identity
     */
    public function lt($value = null)
    {
        $this->operator("<", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function le($value = null)
    {
        $this->operator("<=", $value);
        return $this;
    }

    /**
     * @return Identity
     */
    public function ge($value = null)
    {
        $this->operator(">=", $value);
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

    public function _operatorAttach($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception("No object field defined");
        }

        $this->_currentField->attach($symbol, $value);

        return $this;
    }
}

<?php

namespace G4\DataMapper\Selection;

class IdentityField
{
    private $_comps = array();

    private $_name = null;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function attach($operator, $value)
    {
        return empty($this->_comps)
            ? $this->add($operator, $value)
            : $this->overrideCurrent($operator, $value);
    }

    public function add($operator, $value)
    {
        $this->_comps[] = array(
            'name'     => $this->_name,
            'operator' => $operator,
            'value'    => $value
        );
        return $this;
    }

    public function getComps()
    {
        return $this->_comps;
    }

    public function getCompEq()
    {
        if (!$this->isIncomplete()) {
            foreach ($this->_comps as $comp) {
                if ($comp['operator'] == '=') {
                    return $comp['value'];
                }
            }
        }
        return null;
    }

    public function getCurrentValue()
    {
        return $this->hasCurrentValue()
            ? $this->_comps[0]['value']
            : null;
    }

    public function getCurrentValueMax()
    {
        return $this->hasCurrentValueMax()
            ? $this->_comps[0]['value'][1]
            : null;
    }

    public function getCurrentValueMin()
    {
        return $this->hasCurrentValue()
            ? $this->_comps[0]['value'][0]
            : null;
    }

    public function hasCurrentValue()
    {
        return !empty($this->_comps) && isset($this->_comps[0]['value']);
    }

    public function hasCurrentValueMax()
    {
        return $this->hasCurrentValue() && isset($this->_comps[0]['value'][1]);
    }

    public function hasCurrentValueMin()
    {
        return $this->hasCurrentValue() && isset($this->_comps[0]['value'][0]);
    }

    public function isIncomplete()
    {
        return empty($this->_comps);
    }

    private function overrideCurrent($operator, $value)
    {
        $this->_comps[0] = array(
            'name'     => $this->_name,
            'operator' => $operator,
            'value'    => $value
        );
        return $this;
    }
}

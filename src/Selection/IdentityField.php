<?php

namespace G4\DataMapper\Selection;

class IdentityField
{
    private $comps = array();

    private $name = null;

    public function __construct($name)
    {
        $this->name  = $name;
        $this->comps = [];
    }

    public function attach($operator, $value)
    {
        return empty($this->comps)
            ? $this->add($operator, $value)
            : $this->overrideCurrent($operator, $value);
    }

    public function add($operator, $value)
    {
        $this->comps[] = array(
            'name'     => $this->name,
            'operator' => $operator,
            'value'    => $value
        );
        return $this;
    }

    public function addPrefixToName($prefix)
    {
        $this->name = $prefix . $this->name;
        return $this;
    }

    public function getComps()
    {
        return $this->comps;
    }

    public function getCompEq()
    {
        if (!$this->isIncomplete()) {
            foreach ($this->comps as $comp) {
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
            ? $this->comps[0]['value']
            : null;
    }

    public function getCurrentValueMax()
    {
        return $this->hasCurrentValueMax()
            ? $this->comps[0]['value'][1]
            : null;
    }

    public function getCurrentValueMin()
    {
        return $this->hasCurrentValue()
            ? $this->comps[0]['value'][0]
            : null;
    }

    public function getName()
    {
        return $this->name;
    }

    public function hasCurrentValue()
    {
        return !empty($this->comps) && isset($this->comps[0]['value']);
    }

    public function hasCurrentValueMax()
    {
        return $this->hasCurrentValue() && isset($this->comps[0]['value'][1]);
    }

    public function hasCurrentValueMin()
    {
        return $this->hasCurrentValue() && isset($this->comps[0]['value'][0]);
    }

    public function isIncomplete()
    {
        return empty($this->comps);
    }

    private function overrideCurrent($operator, $value)
    {
        $this->comps[0] = array(
            'name'     => $this->name,
            'operator' => $operator,
            'value'    => $value
        );
        return $this;
    }
}
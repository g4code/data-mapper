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


    public function isIncomplete()
    {
        return empty($this->_comps);
    }
}
<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;

class IdentityAbstract
{

    /**
     * @var array
     */
    private $orderBy = [];

    /**
     * @return array
     */
    public function getOrderBy()
    {
        return $this->orderBy;
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
    public function setOrderBy($param, $value)
    {
        $this->orderBy[$param] = $value;
        return $this;
    }
}
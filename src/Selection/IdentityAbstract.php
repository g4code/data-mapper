<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;

class IdentityAbstract
{

    private $limit = '';

    /**
     * @var array
     */
    private $orderBy = [];

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
     * @return boolean
     */
    public function hasOrderBy()
    {
        return !empty($this->orderBy);
    }

    /**
     * @return Identity
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
}
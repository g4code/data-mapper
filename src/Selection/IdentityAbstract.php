<?php

namespace G4\DataMapper\Selection;

use G4\DataMapper\Selection\IdentityField;

class IdentityAbstract
{

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
}
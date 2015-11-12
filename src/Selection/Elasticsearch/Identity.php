<?php

namespace G4\DataMapper\Selection\Elasticsearch;

class Identity extends \G4\DataMapper\Selection\IdentityAbstract
{

    private $fieldList = [];


    public function equal($value = null)
    {
        return $this->operator(
            'term',
            $value
        );
    }

    public function greaterThan($value)
    {

    }

    public function greaterThanOrEqual($value)
    {

    }

    public function in(array $values = null)
    {
        if (empty($values)) {
            $values = null;
        }
        return $this->operator(
            'terms',
            $values
        );
    }

    public function like($value, $wildCardPosition = null)
    {
        return $this->operator(
            'terms',
            $value
        );
    }

    public function lessThan($value)
    {

    }

    public function lessThanOrEqual($value)
    {

    }

    public function notEqual($value)
    {

    }

    public function notIn(array $values = null)
    {
        if (empty($values)) {
            $values = null;
        }
        return $this->operator(
            'terms',
            $values
        );
    }

    public function setFieldList(array $fieldList)
    {
        $this->fieldList = $fieldList;
        return $this;
    }
}
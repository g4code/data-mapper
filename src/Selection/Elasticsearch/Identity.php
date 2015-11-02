<?php

namespace G4\DataMapper\Selection\Elasticsearch;

class Identity extends \G4\DataMapper\Selection\IdentityAbstract
{

    private $fieldList = [];


    public function eq($value = null)
    {
        return $this->operator(
            'term',
            $value
        );
    }

    public function in(array $values = null)
    {
        return $this->operator(
            'terms',
            $value
        );
    }

    public function setFieldList(array $fieldList)
    {
        $this->fieldList = $fieldList;
        return $this;
    }
}
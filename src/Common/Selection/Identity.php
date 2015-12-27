<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\SelectionIdentityInterface;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\Selection\Field;
use G4\DataMapper\Common\Selection\Sort;

class Identity implements SelectionIdentityInterface
{

    /**
     * @var Field
     */
    private $currentField;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $sorting;


    public function __construct()
    {
        $this->fields  = [];
        $this->sorting = [];
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function equal($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::EQUAL, $value);
        return $this;
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function greaterThan($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::GRATER_THAN, $value);
        return $this;
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function greaterThanOrEqual($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::GRATER_THAN_OR_EQUAL, $value);
        return $this;
    }

    /**
     * @param array $value
     * @return Identity
     */
    public function in(array $value)
    {
        $this->operator(Operator::IN, $value);
        return $this;
    }

    public function like($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::LIKE, $value);
        return $this;
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function lessThan($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::LESS_THAN, $value);
        return $this;
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function lessThanOrEqual($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::LESS_THAN_OR_EQUAL, $value);
        return $this;
    }

    public function notEqual($value)
    {
        $this->arrayException($value);
        $this->operator(Operator::NOT_EQUAL, $value);
        return $this;
    }

    public function notIn(array $value)
    {
        $this->operator(Operator::NOT_IN, $value);
        return $this;
    }

    public function sortAscending($fieldName)
    {
        if ($fieldName !== null) {
            $this->sorting[$fieldName] = new Sort($fieldName, Sort::ASCENDING);
        }
        return $this;
    }

    public function sortDescending($fieldName)
    {
        if ($fieldName !== null) {
            $this->sorting[$fieldName] = new Sort($fieldName, Sort::DESCENDING);
        }
        return $this;
    }

    /**
     * @param string $fieldName
     * @throws \Exception
     * @return Identity
     */
    public function field($fieldName)
    {
        if (!$this->isVoid() && $this->currentField->isIncomplete()) {
            throw new \Exception("Incomplete field", 101);
        }

        if (isset($this->fields[$fieldName])) {
            throw new \Exception("Field is already set", 101);
        }

        $this->currentField       = new Field($fieldName);
        $this->fields[$fieldName] = $this->currentField;

        return $this;
    }

    /**
     * @return array
     */
    public function getComparisons()
    {
        $comparisons = [];
        foreach ($this->fields as $field) {
            if ($field instanceof Field) {
                $comparisons = array_merge($comparisons, $field->getComparisons());
            }
        }
        return $comparisons;
    }

    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @return boolean
     */
    public function isVoid()
    {
        return count($this->fields) === 0;
    }

    private function arrayException($value)
    {
        if (is_array($value)) {
            throw new \Exception('Value cannot be array', 101);
        }
    }

    /**
     * @param string $symbol
     * @param string $value
     * @throws \Exception
     */
    private function operator($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new \Exception('Field is not defined', 101);
        }
        $this->currentField->add(new Operator($symbol), $value);
    }
}
<?php

namespace G4\DataMapper\Common\Selection;

use G4\DataMapper\Common\SelectionIdentityInterface;
use G4\DataMapper\Common\Selection\Operator;
use G4\DataMapper\Common\Selection\Field;

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


    public function __construct()
    {
        $this->fields = [];
    }

    /**
     * @param string $value
     * @return Identity
     */
    public function equal($value)
    {
        $this->operator(Operator::EQUAL, $value);
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

    /**
     * @return boolean
     */
    public function isVoid()
    {
        return count($this->fields) === 0;
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
<?php

namespace G4\DataMapper\Selection\Elasticsearch;

use G4\DataMapper\Selection\Elasticsearch\Consts;

//TODO: Drasko: Extract common logic to new classes!!!
class Identity extends \G4\DataMapper\Selection\IdentityAbstract
{

    private $fieldList = [];


    public function between($min, $max)
    {
        $value = null;
        if ($min !== null && $max !== null) {
            $value = [
                Consts::GREATER_THAN_OR_EQUAL => ($min < $max ? $min : $max),
                Consts::LESS_THAN_OR_EQUAL    => ($max > $min ? $max : $min),
            ];
        }
        return $this->operator(Consts::MUST, $value);
    }

    public function equal($value = null)
    {
        return $this->operator(Consts::MUST, $value);
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
        return $this->operator(Consts::MUST, $values);
    }

    //TODO: Drasko: This needs refactoring !!!
    public function like($value, $wildCardPosition = Consts::WILDCARD_POSITION_BOTH)
    {
        if ($value !== null) {
            if ($wildCardPosition === Consts::WILDCARD_POSITION_BOTH) {
                $value = "*{$value}*";
            }
            if ($wildCardPosition === Consts::WILDCARD_POSITION_LEFT) {
                $value = "*{$value}";
            }
            if ($wildCardPosition === Consts::WILDCARD_POSITION_RIGHT) {
                $value = "{$value}*";
            }
        }
        return $this->operator(Consts::WILDCARD, $value);
    }

    public function lessThan($value)
    {

    }

    public function lessThanOrEqual($value)
    {

    }

    public function notEqual($value)
    {
        return $this->operator(Consts::MUST_NOT, $value);
    }

    public function notIn(array $values = null)
    {
        if (empty($values)) {
            $values = null;
        }
        return $this->operator(Consts::MUST_NOT,$values);
    }

    public function notTimeFromInMinutes($value)
    {
        if ($value !== null) {
            $value = [
                Consts::GREATER_THAN       => 'now' . $value . 'm/m',
                Consts::LESS_THAN_OR_EQUAL => 'now/m',
            ];
        }
        return $this->operator(Consts::MUST_NOT, $value);
    }

    public function setFieldList(array $fieldList)
    {
        $this->fieldList = $fieldList;
        return $this;
    }

    public function timeFromInMinutes($value)
    {
        if ($value !== null) {
            $value = [
                Consts::GREATER_THAN       => 'now' . $value . 'm/m',
                Consts::LESS_THAN_OR_EQUAL => 'now/m',
            ];
        }
        return $this->operator(Consts::MUST, $value);
    }
}
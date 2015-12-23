<?php

namespace G4\DataMapper\Common;

interface SelectionIdentityInterface
{

    public function equal($value);

    public function getComparisons();

    public function isVoid();

    public function field($fieldName);

    public function greaterThan($value);

    public function greaterThanOrEqual($value);

    public function in($value);

    public function like($value);

    public function lessThan($value);

    public function lessThanOrEqual($value);

    public function notEqual($value);

    public function notIn($value);

    public function sort();
}
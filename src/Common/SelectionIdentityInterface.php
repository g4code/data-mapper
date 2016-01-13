<?php

namespace G4\DataMapper\Common;

interface SelectionIdentityInterface
{

    public function equal($value);

    public function field($fieldName);

    public function getComparisons();

    public function getFieldNames();

    public function getGrouping();

    public function getLimit();

    public function getOffset();

    public function getSorting();

    public function greaterThan($value);

    public function greaterThanOrEqual($value);

    public function groupBy($fieldName);

    public function in(array $value);

    public function isVoid();

    public function like($value);

    public function lessThan($value);

    public function lessThanOrEqual($value);

    public function notEqual($value);

    public function notIn(array $value);

    public function sortAscending($fieldName);

    public function sortDescending($fieldName);

    public function setFieldNames(array $fieldNames);
}
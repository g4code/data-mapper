<?php

namespace G4\DataMapper\Common;

interface SelectionIdentityInterface
{

    public function equal($value);

    public function getComparisons();

    public function isVoid();

//     public function field();

//     public function greaterThan();

//     public function greaterThanOrEqual();

//     public function in();

//     public function like();

//     public function lessThan();

//     public function lessThanOrEqual();

//     public function notEqual();

//     public function notIn();

//     public function sort();


}
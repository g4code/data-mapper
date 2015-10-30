<?php

namespace G4\DataMapper\Selection;

interface IdentityInterface
{

    public function equal($value);


    public function greaterThan($value);

    public function greaterThanOrEqual($value);


    public function in(array $fields);

    public function like($value, $wildCardPosition = null);


    public function lessThan($value);

    public function lessThanOrEqual($value);


    public function notEqual($value);

    public function notIn(array $fields);
}
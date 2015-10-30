<?php

namespace G4\DataMapper\Selection;

interface IdentityInterface
{

    public function equal($value = null);

    public function notEqual($value = null);


    public function greaterThan($value = null);

    public function greaterThanOrEqual($value = null);

    public function lessThan($value = null);

    public function lessThanOrEqual($value = null);


    public function in($fields = array());

    public function notIn($fields = array());


    public function like($value = null, $wildCardPosition = null);
}
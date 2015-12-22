<?php

namespace G4\DataMapper\Common;

interface ComparisonFormatterInterface
{

    public function format($name, $operator, $value);

}
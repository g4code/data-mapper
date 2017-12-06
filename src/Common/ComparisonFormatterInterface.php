<?php

namespace G4\DataMapper\Common;

use G4\DataMapper\Common\Selection\Operator;

interface ComparisonFormatterInterface
{

    public function format($name, Operator $operator, $value);
}

<?php

namespace G4\DataMapper\Engine\MySQL;

use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Common\SelectionIdentityInterface;
use G4\DataMapper\Common\Selection\Comparison;
use G4\DataMapper\Engine\MySQL\MySQLComparisonFormatter;

class MySQLSelectionFactory implements SelectionFactoryInterface
{

    /**
     * @var SelectionIdentityInterface
     */
    private $identity;

    /**
     * @param SelectionIdentityInterface $identity
     */
    public function __construct(SelectionIdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fields()
    {

    }

    public function group()
    {

    }

    public function sort()
    {

    }

    public function where()
    {
        if ($this->identity->isVoid()) {
            return '1';
        }

        $comparisons = [];

        foreach ($this->identity->getComparisons() as $oneComparison) {
            if ($oneComparison instanceof Comparison) {
                $comparisons[] = $oneComparison->getComparison($this->makeComparisonFormatter());
            }
        }

        return join(' AND ', $comparisons);
    }

    public function limit()
    {

    }

    public function makeComparisonFormatter()
    {
        return new MySQLComparisonFormatter();
    }

    private function quote($value)
    {
        return $value;
    }
}
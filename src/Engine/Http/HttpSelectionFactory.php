<?php

namespace G4\DataMapper\Engine\Http;

use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\Selection\Comparison;
use G4\DataMapper\Common\SelectionFactoryInterface;
use G4\DataMapper\Exception\MethodNotValidForHttpEngineException;

class HttpSelectionFactory implements SelectionFactoryInterface
{

    /**
     * @var IdentityInterface
     */
    private $identity;

    /**
     * HttpSelectionFactory constructor.
     * @param IdentityInterface $identity
     */
    public function __construct(IdentityInterface $identity)
    {
        $this->identity = $identity;
    }

    public function fieldNames()
    {
        throw new MethodNotValidForHttpEngineException(__CLASS__, __METHOD__);
    }

    public function group()
    {
        throw new MethodNotValidForHttpEngineException(__CLASS__, __METHOD__);
    }

    public function sort()
    {
        throw new MethodNotValidForHttpEngineException(__CLASS__, __METHOD__);
    }

    public function where()
    {
        if ($this->identity->isVoid()) {
            return '';
        }

        $comparisons = [];

        foreach ($this->identity->getComparisons() as $oneComparison) {
            if ($oneComparison instanceof Comparison) {
                $comparisons[] = $oneComparison->getComparison($this->makeComparisonFormatter());
            }
        }
        return join('&', $comparisons);
    }

    public function limit()
    {
        throw new MethodNotValidForHttpEngineException(__CLASS__, __METHOD__);
    }

    public function offset()
    {
        throw new MethodNotValidForHttpEngineException(__CLASS__, __METHOD__);
    }

    public function makeComparisonFormatter()
    {
        return new HttpComparisonFormatter();
    }
}

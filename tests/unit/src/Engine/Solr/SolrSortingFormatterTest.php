<?php

use G4\DataMapper\Engine\Solr\SolrSortingFormatter;
use G4\DataMapper\Common\Selection\Sort;

class SolrSortingFormatterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SolrSortingFormatter
     */
    private $sortingFormatter;

    protected function setUp()
    {
        $this->sortingFormatter = new SolrSortingFormatter();
    }

    protected function tearDown()
    {
        $this->sortingFormatter = null;
    }

    public function testAscendingFormat()
    {
        $this->assertEquals('name asc', $this->sortingFormatter->format('name', Sort::ASCENDING));
    }
}





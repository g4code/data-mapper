<?php

use G4\DataMapper\Engine\Solr\SolrSelectionFactory;

class SolrSelectionFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SolrSelectionFactory
     */
    private $selectionFactory;

    private $identityMock;

    protected function setUp()
    {
        $this->identityMock = $this->getMockBuilder('\G4\DataMapper\Common\Identity')
            ->disableOriginalConstructor()
            ->getMock();

        $this->selectionFactory = new SolrSelectionFactory($this->identityMock);
    }

    protected function tearDown()
    {
        $this->identityMock = null;

        $this->selectionFactory = null;
    }
}

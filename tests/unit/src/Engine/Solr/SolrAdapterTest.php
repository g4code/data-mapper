<?php

class SolrAdapterTest extends PHPUnit_Framework_TestCase
{


    public function testSelect()
    {
        $clientFactory = new \G4\DataMapper\Engine\Solr\SolrClientFactory(['host' => 'localhost', 'port' => '8983']);

        $identity = new \G4\DataMapper\Common\Identity();

        $identity->setLimit(10)->setOffset(1)->field('id')->equal('15500')->sortAscending('name');

        $selectionFactory = new \G4\DataMapper\Engine\Solr\SolrSelectionFactory($identity);

        $solrAdapter = new \G4\DataMapper\Engine\Solr\SolrAdapter($clientFactory);

        $select = $solrAdapter->select(new \G4\DataMapper\Engine\Solr\SolrCollectionName('nd_api'), $selectionFactory);

//        var_dump($select);
        die('ovde');
    }
}

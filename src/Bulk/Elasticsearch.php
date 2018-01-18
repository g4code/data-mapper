<?php

namespace G4\DataMapper\Bulk;

use G4\DataMapper\Selection\Elasticsearch\Factory as SelectionFactory;

class Elasticsearch
{

    private $selectionFactory;

    private $data;



    public function __construct(SelectionFactory $selectionFactory)
    {
        $this->selectionFactory = $selectionFactory;
        $this->data             = [];
    }

    public function getData()
    {
        return [
            'body' => $this->data,
        ];
    }

    public function markForDelete(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->data[] = [
            'delete' => $this->getMetaData($domain->getId()),
        ];
        return $this;
    }

    public function markForSet(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->data[] = [
            'index' => $this->getMetaData($domain->getId()),
        ];
        $this->data[] = [
            'doc' => $domain->getRawData(),
        ];
        return $this;
    }

    private function getMetaData($id)
    {
        return [
            '_index' => $this->selectionFactory->prepareType()['index'],
            '_type'  => $this->selectionFactory->prepareType()['type'],
            '_id'    => $id,
        ];
    }
}

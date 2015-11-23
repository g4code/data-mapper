<?php

namespace G4\DataMapper\Bulk;

use G4\DataMapper\Selection\Elasticsearch\Factory as SelectionFactory;

class Elasticsearch
{

    private $selectionFactory;

    private $data;

    private $idsForDelete;


    public function __construct(SelectionFactory $selectionFactory)
    {
        $this->selectionFactory = $selectionFactory;
        $this->data             = [];
        $this->idsForDelete     = [];
    }

    public function getData()
    {
        return [
            'body' => $this->data,
        ];
    }

    public function markForDelete(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->idsForDelete[] = $domain->getId();
        return $this;
    }

    public function markForSet(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->data[] = [
            'update' => [
                '_index' => $this->selectionFactory->prepareType()['index'],
                '_type'  => $this->selectionFactory->prepareType()['type'],
                '_id'    => $domain->getId(),
            ],
        ];
        $this->data[] = [
            'doc' => $domain->getRawData(),
        ];
        return $this;
    }

}
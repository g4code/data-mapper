<?php

namespace G4\DataMapper\Bulk;

class Solr
{

    const METHOD_ADD     = 'add';
    const METHOD_SET     = 'set';
    const METHOD_DELETE  = 'delete';

    const IDENTIFIER_KEY = 'id';

    private $data;

    private $idsForDelete;


    public function __construct()
    {
        $this->data         = [];
        $this->idsForDelete = [];
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataForDelete()
    {
        $identity = new \G4\DataMapper\Selection\Solr\Identity();
        $identity
            ->field(self::IDENTIFIER_KEY)
            ->in($this->idsForDelete);
        $selection = new \G4\DataMapper\Selection\Solr\Factory();
        return [
            self::METHOD_DELETE => [
                'query' => $selection->query($identity)
            ]
        ];
    }

    public function hasData()
    {
        return !empty($this->data);
    }

    public function hasDataForDelete()
    {
        return !empty($this->idsForDelete);
    }

    public function markForAdd(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->data[] = $this->addMethodToData(self::METHOD_ADD, $domain->getRawData());
        return $this;
    }

    public function markForDelete(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->idsForDelete[] = $domain->getId();
        return $this;
    }

    public function markForSet(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->data[] = $this->addMethodToData(self::METHOD_SET, $domain->getRawData());
        return $this;
    }

    public function markForUpdate(\G4\DataMapper\Domain\DomainAbstract $domain)
    {
        $this->data[] = $domain->getRawData();
        return $this;
    }

    private function addMethodToData($method, array $data)
    {
        foreach($data as $key => $value) {
            if ($key != self::IDENTIFIER_KEY) {
                $data[$key] = [$method => $value];
            }
        }
        return $data;
    }
}
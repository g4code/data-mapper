<?php

namespace G4\DataMapper\Bulk;

class Solr
{

    const METHOD_ADD     = 'add';
    const METHOD_SET     = 'set';
    const METHOD_DELETE  = 'delete';

    const IDENTIFIER_KEY = 'id';

    private $data;

    /**
     * @var \G4\DataMapper\Selection\Solr\Identity
     */
    private $identity;

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
        $this->appendIdsForDeleteToIdentity();
        return [
            self::METHOD_DELETE => [
                'query' => $this->getSelection()->query($this->getIdentity())
            ]
        ];
    }

    public function hasData()
    {
        return !empty($this->data);
    }

    public function hasDataForDelete()
    {
        return $this->hasIdsForDelete()
            || ($this->hasIdentity() && !$this->getIdentity()->isVoid());
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

    public function markForDeleteByIdentity(\G4\DataMapper\Selection\IdentityAbstract $identity)
    {
        $this->identity = $identity;
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

    private function appendIdsForDeleteToIdentity()
    {
        if ($this->hasIdsForDelete()) {
            $this->getIdentity()
                ->field(self::IDENTIFIER_KEY)
                ->in($this->idsForDelete);
        }
    }

    private function getIdentity()
    {
        if (!$this->hasIdentity()) {
            $this->identity = new \G4\DataMapper\Selection\Solr\Identity();
        }
        return $this->identity;
    }

    private function getSelection()
    {
        return new \G4\DataMapper\Selection\Solr\Factory();
    }

    private function hasIdentity()
    {
        return $this->identity instanceof \G4\DataMapper\Selection\Solr\Identity;
    }

    private function hasIdsForDelete()
    {
        return !empty($this->idsForDelete);
    }
}
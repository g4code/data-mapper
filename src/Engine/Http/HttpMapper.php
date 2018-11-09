<?php

namespace G4\DataMapper\Engine\Http;

use G4\DataMapper\Common\AdapterInterface;
use G4\DataMapper\Common\IdentityInterface;
use G4\DataMapper\Common\MapperInterface;
use G4\DataMapper\Common\MappingInterface;
use G4\DataMapper\Exception\HttpMapperException;

class HttpMapper implements MapperInterface
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var HttpPath
     */
    private $httpPath;

    /**
     * HttpMapper constructor.
     * @param AdapterInterface $adapter
     * @param HttpPath $httpPath
     */
    public function __construct(AdapterInterface $adapter, HttpPath $httpPath)
    {
        $this->adapter  = $adapter;
        $this->httpPath = $httpPath;
    }

    public function delete(IdentityInterface $identity)
    {
        // TODO: Implement delete() method.
    }

    public function find(IdentityInterface $identity)
    {
        // TODO: Implement find() method.
    }

    public function insert(MappingInterface $mapping)
    {
        // TODO: Implement insert() method.
    }

    public function update(MappingInterface $mapping, IdentityInterface $identity)
    {
        // TODO: Implement update() method.
    }

    public function upsert(MappingInterface $mapping)
    {
        // TODO: Implement upsert() method.
    }

    public function query($query)
    {
        // TODO: Implement query() method.
    }

    /**
     * @param \Exception $exception
     * @throws HttpMapperException
     */
    private function handleException(\Exception $exception)
    {
        throw new HttpMapperException($exception->getCode() . ': ' . $exception->getMessage());
    }

    /**
     * @param IdentityInterface $identity
     * @return HttpSelectionFactory
     */
    private function makeSelectionFactory(IdentityInterface $identity)
    {
        return new HttpSelectionFactory($identity);
    }
}

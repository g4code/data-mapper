<?php

namespace G4\DataMapper\Collection;

abstract class CollectionAbstract implements \Iterator, \Countable
{
    protected $_keyMap = array();

    protected $_objects = array();

    protected $_pointer = 0;

    protected $_rawData = array();

    protected $_total = 0;

    public function count(): int
    {
        return $this->_total;
    }

    public function current(): mixed
    {
        return $this->_getObject();
    }

    public function getRawData()
    {
        return $this->_rawData;
    }

    public function key(): mixed
    {
        return $this->_pointer;
    }

    public function next(): void
    {
        $row = $this->_getObject();

        if (!empty($row)) {
            $this->_incrementPointer();
        }
    }

    public function rewind(): void
    {
        $this->_pointer = 0;
    }

    public function valid(): bool
    {
        return !is_null($this->current());
    }

    abstract protected function _factory();

    protected function _addObject($object)
    {
        $this->_objects[$this->_pointer] = $object;
        return $this;
    }

    protected function _getCurrentObject()
    {
        return $this->_objects[$this->_pointer];
    }

    protected function _getCurrentRawData()
    {
        return $this->_rawData[$this->_keyMap[$this->_pointer]];
    }

    /**
     * TODO:Drasko - Do this better!
     */
    protected function _getObject()
    {
        if ($this->_pointer >= $this->_total || $this->_pointer < 0) {
            return null;
        }

        if ($this->_hasCurrentObject()) {
            return $this->_getCurrentObject();
        }

        if ($this->_hasCurrentRawData()) {
            $object = $this->_factory();

            if ($object === null) {
                return null;
            }

            $this->_addObject($object);

            return $this->_getCurrentObject();
        }

        return null;
    }

    protected function _hasCurrentObject()
    {
        return isset($this->_objects[$this->_pointer]);
    }

    protected function _hasCurrentRawData()
    {
        return isset($this->_rawData[$this->_keyMap[$this->_pointer]]);
    }

    public function _incrementPointer()
    {
        $this->_pointer++;
        return $this;
    }

    protected function _setRawData(array $rawData = null)
    {
        if (!is_null($rawData)) {
            $this->_rawData = $rawData;
            $this->_total   = count($rawData);
            $this->_keyMap  = array_keys($rawData);
        }
    }
}

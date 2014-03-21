<?php

namespace G4\DataMapper\Mapper\Mysql;

use Gee\Log\Writer;

use G4\DataMapper\Domain\DomainAbstract;
use G4\DataMapper\Mapper\MapperInterface;
use G4\DataMapper\Selection\Factory as SelectionFactory;
use G4\DataMapper\Selection\Identity;

abstract class MysqlAbstract implements MapperInterface
{
    protected static $_throwExceptionOnNewTrancationBegin = false;

    protected static $_transactionActive = false;

    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    protected $_db;

    protected $_factoryDomainName = null;

    protected $_rawData = array();

    /**
     *
     * @var int
     */
    protected $_totalItemsCount;

    /**
     * @var SelectionFactory
     */
    protected $_selectionFactory = null;

    protected $_tableName = null;

    public function __construct()
    {
        $this->_db = \G4\DataMapper\Db\Db::getAdapter();
    }

    public function delete(Identity $identity)
    {
        return $this->_db->delete($this->_getTablaName(), $this->_getSelectionFactory()->where($identity));
    }

    public function transactionBegin()
    {
        if(self::$_throwExceptionOnNewTrancationBegin === true && self::$_transactionActive === true) {
            throw new \Exception('Database transaction is already started');
        }

        self::$_transactionActive = true;
        return $this->_db->beginTransaction();
    }

    public function transactionRollback()
    {
        // reset transaction status flag
        self::$_transactionActive = false;
        return $this->_db->rollBack();
    }

    public function transactionCommit()
    {
        // reset transaction status flag
        self::$_transactionActive = false;
        return $this->_db->commit();
    }


    /**
     * @param Identity $identity
     *
     * @return \Api\Model\Collection\Content
     */
    public function findAll(Identity $identity = null)
    {
        if ($identity === null) {
            $identity = $this->getIdentity();
        }

        return $this
            ->_fetchAll($identity)
            ->_fetchCount($identity)
            ->_returnCollection();
    }

    /**
     * @param Identity $identity
     *
     * @return DomainAbstract
     */
    public function findOne(Identity $identity)
    {
        return $this
            ->_fetchRow($identity)
            ->_returnDomain();
    }

    /**
     * @return Identity
     */
    public function getIdentity()
    {
        return new Identity();
    }


    public function hasRawData()
    {
        return !empty($this->_rawData);
    }


    public function insert(DomainAbstract $domain)
    {
        $this->setRawDataFromDomain($domain);
        $this->_db->insert($this->_getTablaName(), $this->_rawData);
        $lastId = $this->_db->lastInsertId();
        $domain->setId($lastId);

        return isset($lastId) ? $lastId : false;
    }

    public function insertOnDuplicateKeyUpdate(DomainAbstract $domain)
    {
        $this->setRawDataFromDomain($domain);

        $query = 'INSERT INTO `'. $this->_getTablaName().'` ('.implode(',',array_keys($this->_rawData)).') VALUES ('.implode(',',array_fill(1, count($this->_rawData), '?')).')
                  ON DUPLICATE KEY UPDATE '.implode(' = ?,',array_keys($this->_rawData)).' = ?';

        $stmt = $this->_db->query($query,array_merge(array_values($this->_rawData),array_values($this->_rawData)));

        return $stmt->rowCount();
    }

    public function setRawData(array $rawData)
    {
        $this->_rawData = $rawData;
        if (!$this->hasRawData()) {
            throw new \Exception('Raw data is empty');
        }
        return $this;
    }

    /**
     * @param DomainAbstract $domain
     * @throws \Exception
     * @return \G4\DataMapper\Mapper\Mysql\MysqlAbstract
     */
    public function setRawDataFromDomain(DomainAbstract $domain)
    {
        $this->_rawData = $domain->getRawData();
        if (!$this->hasRawData()) {
            throw new \Exception('Domain has no data');
        }
        return $this;
    }

    public function update(DomainAbstract $domain)
    {
        return $this
            ->setRawDataFromDomain($domain)
            ->_update($domain->getIdKey().' = '.$this->_db->quote($domain->getId())); //TODO: Drasko: move this to selection factory!!!
    }

    public function updateAll(Identity $identity, array $rawData)
    {
        foreach($rawData as $key => $value) {
            if(empty($key) || is_numeric($key)) {
                throw new \Exception('Raw data values are not valid');
            }
            $fields[] = "{$key} = ?";
        }

        $sf = $this->_getSelectionFactory();
        $where = $sf->where($identity);

        if ($where == 1) {
            throw new \Exception('Selection identity can not be 1');
        }

        $sql = "UPDATE " . $this->_getTablaName() . " SET " . implode(', ', $fields) . " WHERE {$where}";

        if($identity->getLimit()) {
            $sql .= " LIMIT " . $sf->limit($identity);
        }

        return $this->_db->query($sql , array_values($rawData));
    }

    /**
     * @param Identity $identity
     *
     * @return MysqlAbstract
     */
    protected function _fetchAll(Identity $identity)
    {
        $s = $this->_getSelectWithLimit($identity);

        $this->_rawData = $this->_db->fetchAll($this->_getSelectWithLimit($identity));
        return $this;
    }

    /**
     * @param Identity $identity
     *
     * @return MysqlAbstract
     */
    protected function _fetchCount(Identity $identity)
    {
        $this->_totalItemsCount = $this->_db->fetchOne($this->_getSelectCount($identity));
        return $this;
    }

    /**
     * @param Identity $identity
     *
     * @return MysqlAbstract
     */
    protected function _fetchRow(Identity $identity)
    {
        $this->_rawData = $this->_db->fetchRow($this->_getSelect($identity));
        return $this;
    }

    /**
     * @return string
     */
    protected function _getFactoryDomainName()
    {
        return $this->_factoryDomainName;
    }

    /**
     * @param Identity $identity
     *
     * @return \Zend_Db_Select
     */
    protected function _getSelect(Identity $identity)
    {
        return $this->_db
            ->select()
            ->from($this->_getTablaName(), '*')
            ->where($this->_getSelectionFactory()->where($identity))
            ->order($this->_getSelectionFactory()->orderBy($identity));
    }

    /**
     * @param Identity $identity
     *
     * @return \Zend_Db_Select
     */
    protected function _getSelectCount(Identity $identity)
    {
        return $this->_db
            ->select()
            ->from($this->_getTablaName(), 'COUNT(*) AS cnt')
            ->where($this->_getSelectionFactory()->where($identity))
            ->order($this->_getSelectionFactory()->orderBy($identity));
    }

    /**
     * @param Identity $identity
     *
     * @return \Zend_Db_Select
     */
    protected function _getSelectWithLimit(Identity $identity)
    {
        $sf = $this->_getSelectionFactory();

        return $this->_getSelect($identity)->limit($sf->limit($identity), $sf->offset($identity));
    }

    /**
     * @return SelectionFactory
     */
    protected function _getSelectionFactory()
    {
        if ($this->_selectionFactory === null) {
            $this->_selectionFactory = new SelectionFactory();
        }

        return $this->_selectionFactory;
    }

    /**
     * @return string
     */
    protected function _getTablaName()
    {
        return $this->_tableName;
    }

    /**
     * @return \Api\Model\Collection\Content
     */
    protected function _returnCollection()
    {
        return new \Api\Model\Collection\Content($this->_rawData, $this->_getFactoryDomainName(), $this->_totalItemsCount);
    }

    /**
     * @return
     */
    protected function _returnDomain()
    {
        $factoryDomainName = $this->_getFactoryDomainName();

        $factoryDomain = new $factoryDomainName();

        return empty($this->_rawData) ? null : $factoryDomain->createObject($this->_rawData);
    }

    private function _update($where)
    {
        return $this->_db->update($this->_getTablaName(), $this->_rawData, $where);
    }
}

<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator;

class NewsTable
{

    protected $tableGateway;
    protected $cache;

    public function __construct(TableGateway $tableGateway, $cache)
    {
        $this->tableGateway = $tableGateway;
        $this->cache = $cache;
    }

    public function setCache($cacheKey, $result) {
        if ($this->cache) {
            $result = $this->cache->setItem($cacheKey, $result);
        } 
    }
    
    public function getCache($cacheKey) {
        if ($this->cache) {
            $result = $this->cache->getItem($cacheKey, $success);
        } else {
            $result = null;
        }
        
        return $result;
    }
    
    public function save($data)
    {
        if (!is_array($data)) {
            $hydrator = new Hydrator\ArraySerializable();
            $data = $hydrator->extract($data);
        }

        if (!empty($data['id'])) {
            return $this->tableGateway->update($data, array('id' => $data['id']));
        } else {
            return ($this->tableGateway->insert($data)) ? $this->tableGateway->lastInsertValue : 0;
        }
    }
    
    public function getNews($approved = 1)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->equalTo('approved', $approved);

        $result = $this->tableGateway->selectWith($select)->toArray();

        return isset($result) ? $result : array();
    }
    
    public function getNewsById($newsId)
    {
        $cacheKey = 'news_' . $newsId;

        $result = $this->getCache($cacheKey);
        if (!$result) {
            $select = $this->tableGateway->getSql()->select();
            $select->where->equalTo('id', $newsId);
 
            $result = $this->tableGateway->selectWith($select)->toArray();
            $result = array_pop($result);
            $this->setCache($cacheKey, $result);

        }

        return isset($result) ? $result : array();
    }
    
   
}

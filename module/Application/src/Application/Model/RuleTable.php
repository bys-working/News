<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
//use Zend\Db\Sql\Sql;
//use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
//use Zend\Db\Adapter\Platform\Mysql;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RuleTable implements ServiceLocatorAwareInterface
{
    protected $tableGateway;
    protected $serviceLocator;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getResources()
    {
        return $this->tableGateway->select()->toArray();
    }

    public function getResourcesByRole($role)
    {
        $adapter = $this->getServiceLocator()->get('dbAdapter');
        $platform = $adapter->getPlatform();

        $sql = new Select;

        $leftJoinResources = new Where();
        $leftJoinResources->equalTo('r.resource_id', new Expression('rs.id'));

        $query = $sql
            ->from(array('r' => $this->getTableGateway()->getTable()))
            ->join(array('rs' => $this->getServiceLocator()->get('ResourceTable')->getTableGateway()->getTable()), $leftJoinResources, array('resource'), 'left')
            ->where(array('r.role' => $role))
            ->getSqlString($platform);

        return $adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray();
    }

    public function resourceModify($resources, $role)
    {
        $this->tableGateway->delete(array('role' => $role));

        foreach ($resources as $resource) {

            $status = $this->tableGateway->insert(
                array(
                    'role' => $role,
                    'resource_id' => $resource,
                )
            );

            if ( ! $status) return 0;
        }

        return $status;
    }

    public function getResourceById($id)
    {
        $select = $this->tableGateway->select(array(
            'id' => $id
        ));

        if ($select->count()) {
            return $select->current()->toArray();
        }

        return false;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    public function delete($id)
    {
        if ($id) {
            return $this->tableGateway->delete(array('id' => $id));
        }
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

}

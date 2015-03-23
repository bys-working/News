<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
//use Zend\Db\Sql\Sql;
//use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
//use Zend\Db\Adapter\Platform\Mysql;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserPermissionTable implements ServiceLocatorAwareInterface
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

    public function getPermissionsByUserId($id)
    {
        $adapter = $this->getServiceLocator()->get('dbAdapter');
        $platform = $adapter->getPlatform();

        $sql = new Select;

        $leftJoinResources = new Where();
        $leftJoinResources->equalTo('up.resource_id', new Expression('r.id'));

        $query = $sql
            ->from(array('up' => $this->getServiceLocator()->get('UserPermissionTable')->getTableGateway()->getTable()))
            ->join(array('r' => $this->getServiceLocator()->get('ResourceTable')->getTableGateway()->getTable()), $leftJoinResources, array('resource'), 'left')
            ->where(array('up.user_id' => $id))
            ->getSqlString($platform);

        return $adapter->query($query, $adapter::QUERY_MODE_EXECUTE)->toArray();
    }

    public function resourceModify($resources, $id)
    {
        $this->tableGateway->delete(array('user_id' => $id));
        
        $status = 1;

        foreach ($resources as $resource) {

            $status = $this->tableGateway->insert(
                array(
                    'user_id' => $id,
                    'resource_id' => $resource,
                )
            );

            if ( ! $status) return 0;
        }

        return $status;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

}

<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Sql;
//use Zend\Db\Sql\Predicate;
use Zend\Db\Sql\Expression;
//use Zend\Db\Adapter\Platform\Mysql;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserTable implements ServiceLocatorAwareInterface
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

    public function getUsers()
    {
        return $this->tableGateway->select()->toArray();
    }

    public function getUsersAndRoles()
    {
        $adapter = $this->getTableGateway()->getAdapter();
        $platform = $adapter->getPlatform();
        $sql = new Sql($adapter);
        $sm = $this->getServiceLocator();

        $leftJoin = new Where();
        $leftJoin->equalTo('u.user_id', new Expression('url.user_id'));

        $query = $sql->select()
            ->columns(array(
                'user_id', 'email'
            ))
            ->from(array('u' => $this->getTableGateway()->getTable()))
            ->join(array('url' => $sm->get('UserRoleTable')->getTableGateway()->getTable()), $leftJoin, array(
                'role_id',
                ), 'left')
            ->where(array())
            ->order('email ASC')
            ->getSqlString($platform);

        $resultSet = $adapter->query($query, $adapter::QUERY_MODE_EXECUTE);

        return $resultSet->toArray();
    }

    public function getUserById($id)
    {
        $select = $this->tableGateway->select(array(
            'user_id' => $id
        ));

        if ($select->count()) {
            return $select->current();
        }

        return false;
    }

//    public function getUserByUsername($username)
//    {
//        $select = $this->tableGateway->select(array(
//            'user_id' => $id
//        ));
//
//        if ($select->count()) {
//            return $select->current();
//        }
//
//        return false;
//    }

    public function getUserEmailsOptions()
    {
        $options = array();
        $users = $this->getUsers();

        foreach($users as $user) {
            $options[$user['user_id']] = $user['username'];
        }

        return $options;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }

}

<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator;

class UserRoleTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getRoles()
    {
        return $this->tableGateway->select()->toArray();
    }

    public function getRoleByUserId($id)
    {
        $select = $this->tableGateway->select(array(
            'user_id' => $id
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

    public function getRolesOptions()
    {
        $res = $this->getRoles();
        $options = array();
        foreach ($res as /*$key =>*/ $value) {
            $options[$value['role_id']] = $value['role_id'];
        }

        return $options;
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

        if (isset($data['user_id'])) {
            $this->tableGateway->delete(array('user_id' => $data['user_id']));
        }

        return $this->tableGateway->insert($data);
    }

}

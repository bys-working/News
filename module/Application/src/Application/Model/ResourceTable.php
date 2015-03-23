<?php

namespace Application\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator;

class ResourceTable
{

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getResources()
    {
        return $this->tableGateway->select()->toArray();
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

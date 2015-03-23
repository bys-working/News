<?php

namespace Application\Model;

class UserPermission
{

    public $user_id;
    public $resource_id;

    public function exchangeArray($data)
    {
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->resource_id = (isset($data['resource_id'])) ? $data['resource_id'] : null;
    }

    public function toArray()
    {
        return array(
            'user_id' => $this->user_id,
            'resource_id' => $this->resource_id,
        );
    }

}
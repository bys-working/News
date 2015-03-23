<?php

namespace Application\Model;

class User
{

    public $user_id;
    public $username;
    public $email;

    public function exchangeArray($data)
    {
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
    }

    public function toArray()
    {
        return array(
            'user_id' => $this->user_id,
            'username' => $this->username,
            'email' => $this->email,
        );
    }

}
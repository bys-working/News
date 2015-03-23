<?php

namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
//use Zend\InputFilter\InputFilterAwareInterface;
//use Zend\InputFilter\InputFilterInterface;

class UserRole
{

    public $user_id;
    public $role_id;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->user_id = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->role_id = (isset($data['role_id'])) ? $data['role_id'] : null;
    }

    public function toArray()
    {
        return array(
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
        );
    }

    public function getArrayCopy()
    {
        return $this->toArray();
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                    'name' => 'role_id',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
<?php

namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
//use Zend\InputFilter\InputFilterAwareInterface;
//use Zend\InputFilter\InputFilterInterface;

class Role
{

    public $id;
    public $role_id;
    public $is_default;
    public $parent;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->role_id = (isset($data['role_id'])) ? $data['role_id'] : null;
        $this->is_default = (isset($data['is_default'])) ? $data['is_default'] : 0;
        $this->parent = (isset($data['parent'])) ? $data['parent'] : null;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'role_id' => $this->role_id,
            'is_default' => $this->is_default,
            'parent' => $this->parent,
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

            $inputFilter->add($factory->createInput(array(
                    'name' => 'parent',
                    'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
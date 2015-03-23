<?php

namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
//use Zend\InputFilter\InputFilterAwareInterface;
//use Zend\InputFilter\InputFilterInterface;

class Rule
{

    public $id;
    public $resource_id;
    public $role;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->resource_id = (isset($data['resource_id'])) ? $data['resource_id'] : null;
        $this->role = (isset($data['role'])) ? $data['role'] : null;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'resource_id' => $this->resource_id,
            'role' => $this->role,
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
                    'name' => 'resource',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
            )));

            $inputFilter->add($factory->createInput(array(
                    'name' => 'id',
                    'required' => false,
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
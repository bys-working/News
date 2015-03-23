<?php

namespace Application\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
//use Zend\InputFilter\InputFilterAwareInterface;
//use Zend\InputFilter\InputFilterInterface;

class Resource
{

    public $id;
    public $resource;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->resource = (isset($data['resource'])) ? $data['resource'] : null;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'resource' => $this->resource,
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